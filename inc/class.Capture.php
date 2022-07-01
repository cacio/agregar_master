<?php
class Capture
{

    public function __construct(){

    }

    /**
     * Capture web screenshot using google api.
     *
     * @param (string) $url Valid url
     *
     * @return blob
     */
    public function snap($url)
    {
        //Url value should not empty and validate url
        if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            $curl_init = curl_init("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url={$url}&key=AIzaSyD7AE790GFEr5cUp2waqXCC186rDV60as4");
            curl_setopt($curl_init, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl_init);
            curl_close($curl_init);
            //call Google PageSpeed Insights API
            //decode json data
            
            $googlepsdata = json_decode($response, true);
           
            //screenshot data
            $snapdata = $googlepsdata["lighthouseResult"]["audits"]["full-page-screenshot"]["details"];
            $snap = $snapdata['screenshot']['data'];
            //$snap = str_replace(['_', '-'], ['/', '+'], $snap);
            
            return $snap;
        } else {
            return false;
        }
    }

    public function imagesaver($caminho,$image_data){

        list($type, $data) = explode(';', $image_data); // exploding data for later checking and validating 

        if (preg_match('/^data:image\/(\w+);base64,/', $image_data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                throw new \Exception('invalid image type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $fullname = $caminho.".".$type;

        if(file_put_contents($fullname, $data)){
            $result = $fullname;
        }else{
            $result =  "error";
        }
        /* it will return image name if image is saved successfully 
        or it will return error on failing to save image. */
        return $result; 
}
}

    

?>