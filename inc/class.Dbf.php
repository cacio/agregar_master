<?php
require_once('inc.autoload.php');
class Dbf{
    
    private $mesano;
    private $cnpjemp;
    private $caminho;
    private $caminho2;
    private $pastamesano;
    private $arquivos = array();

    public function __construct($dados = array()){
        
        $this->mesano      = $dados['mesano'];
        $this->cnpjemp     = $dados['cnpjemp'] ;
        $this->caminho     = $dados['caminho'];
        $this->caminho2    = $dados['caminho2'];
        $this->pastamesano = $dados['pastamesano'];

        if(!is_dir("{$this->caminho2}")){				
            mkdir("{$this->caminho2}", 0777, true);
          }

          if(!is_dir("{$this->caminho2}{$this->pastamesano}")){				
            mkdir("{$this->caminho2}{$this->pastamesano}", 0777, true);
          }
          
          array_push($this->arquivos,array(
              'EMPRESAS.dbf',
              'FOLHA.dbf',
              'FUNDESA.dbf',
              'FUNDOVI.dbf',
              'GUIAICMS.dbf',
              'NOTASEN1.dbf',
              'NOTASENT.dbf',
              'NOTASSA1.dbf',
              'NOTASSAI.dbf',
              'RESUMO.dbf',
              'FRIGORIF.dbf',
          ));

    }

    public function DbfCliente(){

        $msg = "";

        $defCliente = array(
            array("CNPJ",'C',14,0),
            array("INSC",'C',14,0),
            array("NOME",'C',40,0),
            array("CIDADE",'C',35,0),
            array("ESTADO",'C',02,0),
            array("VAG",'C',01,0),
          );

        if (!dbase_create($this->caminho.'EMPRESAS.dbf', $defCliente)) {
           $msg = "Erro ao criar database CLIENTE\n";
        }

        $dbemp  = dbase_open($this->caminho.'EMPRESAS.dbf', 2);

        $daoemp = new EmpresasTxtDAO();
        $vetemp = $daoemp->ListaEmpresas($this->cnpjemp);
        $numemp = count($vetemp);
        if($numemp > 0){
            for($i = 0; $i < $numemp; $i++){

                $emp = $vetemp[$i];

                
                $cnpj_cpf      = $emp->getCnpjCpf();
                $insc_estadual = $emp->getInscEstadual();
                $razao         = $emp->getRazao();
                $cidade        = $emp->getCidade();
                $uf            = $emp->getUf();                    
                $tipo          = $emp->getTipo();

                dbase_add_record($dbemp, array(
                $cnpj_cpf, 
                $insc_estadual, 
                $razao, 
                $cidade,
                $uf,
                $tipo));

            }

            $msg = "DBF CLIENTE CRIADO COM SUCESSO! <br>";
        }else{
            $msg = "Não existe dados de cliente pra gerar os DBF EMPRESAS <br>"; 
        }
      
        return $msg;
    }

    public function DbfFolha(){

        $msgf = "";
        $defFolha = array(
            array("COMPETENC",'C',05,0),
            array("NUMEFUNC",'N',5,0),
            array("NUMEFUNC",'N',15,2),                    
          );

        if (!dbase_create($this->caminho.'FOLHA.dbf', $defFolha)) {
            $msgf = "Erro ao criar database FOLHA\n";
        }

        $dbfolha = dbase_open($this->caminho.'FOLHA.dbf', 2);

        $daofolha = new FolhaTxtDAO();
        $vetfolha = $daofolha->ListaFolhaEmpresas($this->cnpjemp);
        $numfolha = count($vetfolha);
        
        if($numfolha > 0){

        for($i = 0; $i < $numfolha; $i++){

            $folha            = $vetfolha[$i];
            $data_pag         = $folha->getDataPag();
            $num_funcionarios = $folha->getNumFuncionario();
            $valor_folha      = $folha->getValorFolha();

            dbase_add_record($dbfolha, array(
                                        date('m/Y',strtotime($data_pag)), 
                                        $num_funcionarios, 
                                        $valor_folha
                                        ));                       

        }

            $msgf = "DBF FOLHA CRIADO COM SUCESSO! <br>";
        }else{
            $msgf = "Não existe dados de folha pra gerar os DBF FOLHA <br>"; 
        }

        return $msgf;
    }

    public function DbfFundesa(){

        $msgfun = "";
        $defFundesa = array(
            array("COMPETENC",'C',05,0),
            array("NUMERO",'C',10,0),
            array("VALORPAGO",'N',15,2),
            array("DATAPAGO",'D',8,0),                    
          );

        if (!dbase_create($this->caminho.'FUNDESA.dbf', $defFundesa)) {
            $msgfun = "Erro ao criar database FUNDESA\n";
        }

        $dbfundesa = dbase_open($this->caminho.'FUNDESA.dbf', 2);
        $daofundesa = new FundesaDAO();
        $vetfundesa = $daofundesa->ListaFundesaEmpresas($this->cnpjemp);
        $numfundesa = count($vetfundesa);

        if($numfundesa > 0){

        for($i = 0; $i < $numfundesa; $i++){

            $fun       = $vetfundesa[$i];
            $competenc = $fun->getCompetenc();
            $numero    = $fun->getNumero();
            $valorpago = $fun->getValorPago();
            $datapago  = $fun->getDataPago();

            dbase_add_record($dbfundesa, array(
            $competenc, 
            $numero, 
            $valorpago,
            date('d/m/Y',strtotime($datapago))
            ));  

        }                    
            $msgfun = "DBF FUNDESA CRIADO COM SUCESSO! <br>";
        }else{
            $msgfun = "Não existe dados de FUNDESA pra gerar os DBF FUNDESA <br>"; 
        }
    
        return $msgfun;
    }

    public function DbfFonduvi(){

        $msgfon = "";
        $defFundovi = array(
            array("COMPETENC",'C',05,0),
            array("NUMERO",'C',1,0),
            array("VALORPAGO",'N',15,2),
            array("DATAPAGO",'D',8,0),                    
        );
         
        if (!dbase_create($this->caminho.'FUNDOVI.dbf', $defFundovi)) {
            $msgfon = "Erro ao criar database FUNDOVI\n";
        }

        $dbfundovi = dbase_open($this->caminho.'FUNDOVI.dbf', 2);  

        $daofundovi =  new FundoviDAO();
        $vetfundovi = $daofundovi->ListaFundoviEmpresas($this->cnpjemp);
        $numfundovi = count($vetfundovi);

        if($numfundovi > 0){

        for($i = 0; $i < $numfundovi; $i++){

            $fund        = $vetfundovi[$i];
            $competenc_f = $fund->getCompetenc();
            $numero_f    = $fund->getNumero();
            $valorpago_f = $fund->getValorPago();
            $datapago_f  = $fund->getDataPago();

            dbase_add_record($dbfundovi, array(
                        $competenc_f, 
                        $numero_f, 
                        $valorpago_f,
                        date('d/m/Y',strtotime($datapago_f))
                        ));

        }

            $msgfon = "DBF FUNDOVI CRIADO COM SUCESSO! <br>";
        }else{
            $msgfon = "Não existe dados de FUNDOVI pra gerar os DBF FUNDOVI <br>";
        }
        
        return $msgfon;
    }


    public function DbfGuiaIcms(){
        $msgguia = "";
        $defGuiaIcms = array(
            array("COMPETENC", 'C',05,0),
            array("NUMERO",     'C',10,0),
            array("CODIGO",     'C',03,0),
            array("VALORPAGO",  'N',15,2),
            array("DATAPAGO",    'D',8,0),                    
        );

        if (!dbase_create($this->caminho.'GUIAICMS.dbf', $defGuiaIcms)) {
            $msgguia = "Erro ao criar database GUIAICMS\n";
        }

        $dbguiaicms = dbase_open($this->caminho.'GUIAICMS.dbf', 2); 


        $daoguiaicms = new GuiaicmsDAO();
        $vetguiaicms = $daoguiaicms->ListaGuiaicmsEmpresas($this->cnpjemp);
        $numguiaicms = count($vetguiaicms);
        
        if($numguiaicms > 0){

        for($i=0; $i< $numguiaicms; $i++){

            $guia      = $vetguiaicms[$i];
            $competenc = $guia->getCompetenc();
            $numero    = $guia->getNumero();
            $codigo    = $guia->getCodigoGuia();
            $valorpago = $guia->getValorPago();
            $datapago  = $guia->getDataPago();

            dbase_add_record($dbguiaicms, array(
                            $competenc, 
                            $numero, 
                            $codigo,
                            $valorpago,                                        
                            date('d/m/Y',strtotime($datapago))
                            ));

        }
            $msgguia = "DBF GUIAICMS CRIADO COM SUCESSO! <br>";
        }else{
            $msgguia = "Não existe dados de GUIAICMS pra gerar os DBF GUIAICMS <br>";
        }

        return $msgguia;
    }


    public function DbfNotasen1(){
        
        $msgnts1 = "";
        $defnotasen1 = array(
            array("NFISCA",'C',10,0),
            array("EMISSAO",'C',10,0),
            array("CLIENTE",'C',14,0),
            array("PRODUTO",'C',14,0),
            array("QUANTCAB",'N',5,0),
            array("PESOVIVO",'N',15,3),
            array("PESOCARC",'N',15,3),
            array("PRECOKILO",'N',15,2),
            array("ITEM",'C',03,0),
            array("INSCRICAO",'C',14,0),
            array("DATAABATE",'C',10,0),
            array("VIVOREND",'C',01,0),
            array("INTEGRA",'C',01,0),                    
        );


        if (!dbase_create($this->caminho.'NOTASEN1.dbf', $defnotasen1)) {
            $msgnts1 = "Erro ao criar database NOTASEN1\n";
        }

        $dbnotasen1 = dbase_open($this->caminho.'NOTASEN1.dbf', 2);

        $daonotasen1 = new NotasEn1TxtDAO();
        $vetnotasen1 = $daonotasen1->ListaNotasEntradaDetalheDBF($this->cnpjemp,$this->mesano);
        $numnotasen1 = count($vetnotasen1);

        if($numnotasen1 > 0){

        for($i = 0; $i < $numnotasen1; $i++){

            $notasen1         = $vetnotasen1[$i]; 
            $numero_nota      = $notasen1->getNumeroNota();
            $data_emissao     = $notasen1->getDataEmissao();
            $cnpj_cpf         = $notasen1->getCnpjCpf();
            $codigo_produto   = $notasen1->getCodigoProduto();
            $qtd_cabeca       = $notasen1->getQtdCabeca();
            $peso_vivo_cabeca = $notasen1->getPesoVivoCabeca();
            $peso_carcasa     = $notasen1->getPesoCarcasa();
            $preco_quilo      = $notasen1->getPrecoQuilo();
            $numero_item_nota = $notasen1->getNumeroItemNota();
            $insc_estadual    = $notasen1->getInsEstadual();
            $data_abate       = $notasen1->getDataAbate();
            $tipo_r_v         = $notasen1->getTipo_R_V();
            $cfop             = $notasen1->getCfop();
            $aliquota_icms    = $notasen1->getAliquotaIcms();

            dbase_add_record($dbnotasen1, array(
            $numero_nota, 
            date('d/m/Y',strtotime($data_emissao)), 
            $cnpj_cpf,
            $codigo_produto,                                        
            $qtd_cabeca,
            $peso_vivo_cabeca,
            $peso_carcasa,
            $preco_quilo,
            $numero_item_nota,
            $insc_estadual,
            date('d/m/Y',strtotime($data_abate)),
            $tipo_r_v,
            'A',
            ));

            
        }
            $msgnts1 = "DBF NOTASEN1 CRIADO COM SUCESSO! <br>";
        }else{
            $msgnts1 = "Não existe dados de NOTASEN1 pra gerar os DBF NOTASEN1 na competencia informada! <br>";
        }

        return $msgnts1;
    }

    public function DbfNotasent(){

        $msgnte = "";
        $defnotasent = array(
            array("NFISCA", 'C',10,0),
            array("EMISSAO",'C',10,0),
            array("CLIENTE",'C',14,0),
            array("VIVOREND",'C',01,0),
            array("VTNOTA",'N',15,2),
            array("GTA",'C',06,0),
            array("NPRODUTORI",'C',06,0),
            array("NPRODUTORF",'C',06,0),
            array("CONDENAS",'C',01,0),
            array("ABATEPT",'C',01,0),
            array("INSCRICAO",'C',14,0),                    
            array("INTEGRA",'C',1,0),                    
        );

        if (!dbase_create($this->caminho.'NOTASENT.dbf', $defnotasent)) {
            $msgnte = "Erro ao criar database NOTASENT\n";
        }

        $dbnotasent = dbase_open($this->caminho.'NOTASENT.dbf', 2);

        $daonotasent = new NotasEntTxtDAO();
        $vetnotasent = $daonotasent->ListaNotasEntrada($this->cnpjemp,$this->mesano);
        $numnotasent = count($vetnotasent);

        if($numnotasent > 0){

            for($i = 0; $i < $numnotasent; $i++){

                $notasent                 = $vetnotasent[$i];
                $numero_nota              = $notasent->getNumeroNota();
                $data_emissao             = $notasent->getDataEmissao();
                $cnpj_cpf                 = $notasent->getCnpjCpf();
                $tipo_v_r_a               = $notasent->getTipoV_R_A();
                $valor_total_nota         = $notasent->getValorTotalNota();
                $gta                      = $notasent->getGta();
                $numero_nota_produtor_ini = $notasent->getNumeroNotaProdutorIni();
                $numero_nota_produtor_fin = $notasent->getNumeroNotaProdutorFin();
                $condenas                 = $notasent->getCondenas();
                $abate                    = $notasent->getAbate();
                $insc_produtor            = $notasent->getInscProdutor();

                dbase_add_record($dbnotasent, array(
                $numero_nota, 
                date('d/m/Y',strtotime($data_emissao)), 
                $cnpj_cpf,
                $tipo_v_r_a,                                        
                $valor_total_nota,
                $gta,
                $numero_nota_produtor_ini,
                $numero_nota_produtor_fin,
                $condenas,
                $abate,
                $insc_produtor,                        
                'A',
                ));

            }

            $msgnte = "DBF NOTASENT CRIADO COM SUCESSO! <br>";
        }else{
            $msgnte = "Não existe dados de NOTASENT pra gerar os DBF NOTASENT na competencia informada! <br>";
        }

        return $msgnte;
    }

    public function DbfNotassa1(){

        $msgntsa1 = "";
        $defnotassa1 = array(
            array("NFISCA", 'C',10,0),
            array("EMISSAO",'C',10,0),
            array("CLIENTE",'C',14,0),
            array("PRODUTO",'C',14,0),
            array("PECAS",'N',15,3),
            array("PESO",'N',15,3),
            array("PRECOKILO",'N',15,2),
            array("ENTSAI",'C',01,0),
            array("ITEM",'C',03,0),
            array("INSCRICAO",'C',14,0),                                      
        );

        if (!dbase_create($this->caminho.'NOTASSA1.dbf', $defnotassa1)) {
            $msgntsa1 = "Erro ao criar database NOTASSA1\n";
        }

        $dbnotassa1 = dbase_open($this->caminho.'NOTASSA1.dbf', 2);

        $daonotassa1 = new NotasSa1TxtDAO();
        $vetnotassa1 = $daonotassa1->ListaNotasSa1DetalheDBF($this->cnpjemp,$this->mesano);
        $numnotassa1 = count($vetnotassa1);
        
        if($numnotassa1 > 0){

        for($i = 0; $i < $numnotassa1; $i++){

            
            $notasa1          =  $vetnotassa1[$i];  
            $numero_nota      = $notasa1->getNumeroNota();
            $data_emissao     = $notasa1->getDataEmissao();
            $cnpj_cpf         = $notasa1->getCnpjCpf();
            $codigo_produto   = $notasa1->getCodigoProduto();
            $qtd_pecas        = $notasa1->getQtdPecas();
            $peso             = $notasa1->getPeso();
            $preco_unitario   = $notasa1->getPrecoUnitario();
            $ent_sai          = $notasa1->getEntSai();
            $numero_item_nota = $notasa1->getNumeroItemNota();		
            $insc_estadual    = $notasa1->getInscEstadual();

            dbase_add_record($dbnotassa1, array(
                $numero_nota, 
                date('d/m/Y',strtotime($data_emissao)), 
                $cnpj_cpf,
                $codigo_produto,                                        
                $qtd_pecas,
                $peso,
                $preco_unitario,
                $ent_sai,
                $numero_item_nota,
                $insc_estadual,                          
            ));

        }
             $msgntsa1 = "DBF NOTASSA1 CRIADO COM SUCESSO! <br>"; 
        }else{
             $msgntsa1 = "Não existe dados de NOTASSA1 pra gerar os DBF NOTASSA1 na competencia informada! <br>";
        }

        return $msgntsa1;
    }

    public function DbfNotassai(){
        $mssnotassai = "";
        $defnotassai = array(
            array("NFISCA", 'C',10,0),
            array("EMISSAO",'C',10,0),
            array("CLIENTE",'C',14,0),
            array("VTNOTA",'N',15,2),
            array("ICMSNOR",'N',15,2),
            array("SUBSTIT",'N',15,2),
            array("ENTSAI",'C',01,0),                    
            array("INSCRICAO",'C',14,0),                                      
        );

        if (!dbase_create($this->caminho.'NOTASSAI.dbf', $defnotassai)) {
            $mssnotassai = "Erro ao criar database NOTASSAI\n";
        }

        $dbnotassai = dbase_open($this->caminho.'NOTASSAI.dbf', 2);
        $daonotassai = new NotasSaiTxtDAO();
        $vetnotassai = $daonotassai->ListandoNotasSaiDBF($this->mesano,$this->cnpjemp);
        $numnotassai = count($vetnotassai); 

        if($numnotassai > 0){

            for($i = 0; $i < $numnotassai; $i++){

                $notasai          = $vetnotassai[$i];
                $numero_nota      = $notasai->getNumeroNota();
                $data_emissao     = $notasai->getDataEmissao();
                $cnpj_cpf         = $notasai->getCnpjCpf();
                $valor_total_nota = $notasai->getValorTotalNota();	
                $valor_icms       = $notasai->getValorIcms();
                $valor_icms_subs  = $notasai->getValorIcmsSubs();
                $ent_sai          = $notasai->getEntSai();
                $insc_estadual    = $notasai->getInscEstadual();

                dbase_add_record($dbnotassai, array(
                $numero_nota, 
                date('d/m/Y',strtotime($data_emissao)), 
                $cnpj_cpf,
                $valor_total_nota,                                        
                $valor_icms,
                $valor_icms_subs,                        
                $ent_sai,                        
                $insc_estadual,                          
                ));


            }
            $mssnotassai = "DBF NOTASSAI CRIADO COM SUCESSO! <br>"; 
        }else{
            $mssnotassai = "Não existe dados de NOTASSAI pra gerar os DBF NOTASSAI na competencia informada! <br>"; 
        }

        return $mssnotassai;
    }

    public function DbfResumo(){
        $msgres = "";
        $defresumo = array(
            array("COMPETENC",'C',5,0),
            array("BOVINOS",'N',5,0),
            array("BUBALINOS",'N',5,0),
            array("OVINOS",'N',5,0),
            array("ICMSNOR",'N',15,2),
            array("SUBSTIT",'N',15,2),
            array("CREDITOENT",'N',15,2),                    
            array("CREDITOSRS",'N',15,2),
            array("CREDITOSOE",'N',15,2),
            array("BASEENT",'N',15,2),
            array("BASESAIRS",'N',15,2),
            array("BASESAIOE",'N',15,2),
            array("NUMEFUNC",'N',5,0),
            array("VALOFOLHA",'N',15,2),
            array("DATAPAGT",'D',8,0),
            array("BASESAIRS4",'N',15,2),                    
            array("CREDITOSR4",'N',15,2),
			array("CREDITOOE4",'N',15,2),
			array("BASESAIOE4",'N',15,2),			
        );

        if (!dbase_create($this->caminho.'RESUMO.dbf', $defresumo)) {
            $msgres = "Erro ao criar database RESUMO\n";
          }

        $dbresumo = dbase_open($this->caminho.'RESUMO.dbf', 2);

        $daoresumo = new ResumoDAO();
        $vetresumo = $daoresumo->ListaResumoEmpresa($this->mesano,$this->cnpjemp);
        $numresumo = count($vetresumo);

        if($numresumo > 0){
            for($i=0; $i < $numresumo; $i++){

                $resu        = $vetresumo[$i];
                $COMPETENC   = substr($resu->getCompetenc(), 0,2).'/'.substr($resu->getCompetenc(), -2);
                $BOVINOS     = $resu->getBovinos();
                $BUBALINOS   = $resu->getBubalinos();
                $OVINOS      = $resu->getOvinos();
                $ICMSNOR     = $resu->getIcmsNor();
                $SUBSTIT     = $resu->getSubstit();
                $CREDITOENT  = $resu->getCreditoEnt();
                $CREDITOSRS  = $resu->getCreditosRS();
                $CREDITOSOE  = $resu->getCreditosOE();
                $BASEENT     = $resu->getBaseEnt();
                $BASESAIRS   = $resu->getBaseSaiRS();
                $BASESAIOE   = $resu->getBaseSaiOE();
                $NUMEFUNC    = $resu->getNumeroFuncionario();
                $VALOFOLHA   = $resu->getValorFolha();
                $DATAPAGT    = $resu->getDataPagto();
                $BASESAIRS4  = $resu->getBaseSaiRS4();
                $CRIDITOSR4  = $resu->getCriditosR4();
                $CREDITOSR4  = $resu->getCreditosR4();
				$CREDITOSOE4 = $resu->getCreditosOE4();
				$BASESAIOE4  = $resu->getBaseSaiOE4();
			
                dbase_add_record($dbresumo, array(
                $COMPETENC, 
                $BOVINOS, 
                $BUBALINOS,
                $OVINOS,                                        
                $ICMSNOR,
                $SUBSTIT,                        
                $CREDITOENT,                        
                $CREDITOSRS,
                $CREDITOSOE,                          
                $BASEENT,
                $BASESAIRS,
                $BASESAIOE,
                $NUMEFUNC,
                $VALOFOLHA,
                date('d/m/Y',strtotime($DATAPAGT)),
                $BASESAIRS4,                            
                $CREDITOSR4,
				$CREDITOSOE4,
				$BASESAIOE4
                ));
                
            }
            
            $msgres = "DBF RESUMO CRIADO COM SUCESSO! <br>";
        }else{
            $msgres = "Não existe dados de RESUMO pra gerar os DBF RESUMO na competencia informada! <br>";
        }

        return $msgres;
    }

    public function DbfFrigorif(){

        $msgfrig     = "";

        $deffrigorif = array(
            array("CNPJ", 'C',14,0),
            array("INSC",'C',14,0),
            array("NOME",'C',40,0),
            array("CIDADE",'C',35,0),
            array("ESTADO",'C',02,0),
            array("TIPISEM",'C',01,0),
            array("REGINSP",'C',10,0),                    
            array("MEDVETE",'C',40,0),
            array("NUMCRMV",'C',10,0),
            array("CAPDIAB",'N',05,0),
            array("RALIZSN",'C',01,0),
            array("PERABTE",'N',6,2),
            array("INSTPAA",'C',1,0),                                                  
        );

        if (!dbase_create($this->caminho.'FRIGORIF.dbf', $deffrigorif)) {
            $msgfrig = "Erro ao criar database FRIGORIF\n";
        }

        $dbfrigorif = dbase_open($this->caminho.'FRIGORIF.dbf', 2);

        $daoemps = new EmpresasDAO();
        $vetemps = $daoemps->ListaEmpresaUmCnpj($this->cnpjemp);
        $numemps = count($vetemps);

        if($numemps > 0){
        
            for($i = 0; $i < $numemps; $i++){

                $emps                = $vetemps[$i];

                $cod                 = $emps->getCodigo();				
                $cnpj                = $emps->getCnpj();
                $razao_social        = $emps->getRazaoSocial();
                $fantasia            = $emps->getFantasia();
                $marca               = $emps->getMarca();
                $ins_estadual        = $emps->getInscricaoEstadual();
                $endereco            = $emps->getEndereco();
                $nro                 = $emps->getNumero();
                $complemento         = $emps->getComplemento();
                $cep                 = $emps->getCep();
                $cidade              = $emps->getCidade();
                $estado              = $emps->getEstado();
                $bairro              = $emps->getBairro();
                $inspecao            = $emps->getInspecao();
                $fone1               = $emps->getFone1();
                $fone2               = $emps->getFone2();
                $email               = $emps->getEmail();
                $responsavel         = $emps->getResponsavel();
                $id_modalidade       = $emps->getIdModalidade();
                $capacidade_bovinos  = $emps->getCapacidadeBovino();
                $capacidade_ovinos   = $emps->getCapacidadeOvino();
                $dt_num_arq_ult_ato  = $emps->getDtAtoJuntaComercial();
                $form_const_juridica = $emps->getFormConstituicaoJuridica();
                $cap_social_reg      = $emps->getCapSocialReg();
                $ativo               = $emps->getAtivo();
                $capacidade_bubalino = $emps->getCapacidadeBubalino();

                dbase_add_record($dbfrigorif, array(
                $cnpj, 
                $ins_estadual, 
                $razao_social,
                $cidade,                                        
                $estado,
                'S',                        
                $inspecao,                        
                '',
                '',                          
                '',
                '',
                '',
                '',                        
                ));
            }
            
            $msgfrig = "DBF FRIGORIF CRIADO COM SUCESSO! <br>";

        }else{
            $msgfrig = "Não existe dados de FRIGORIF pra gerar os DBF FRIGORIF na competencia informada! <br>";
        }

        return $msgfrig;
    }


    public function GerarDbfs(){

        $this->DbfCliente();
        $this->DbfFolha();
        $this->DbfFonduvi();
        $this->DbfFrigorif();
        $this->DbfFundesa();
        $this->DbfGuiaIcms();
        $this->DbfNotasen1();
        $this->DbfNotasent();
        $this->DbfNotassa1();
        $this->DbfNotassai();
        $this->DbfResumo();        
        
        return "Gerado";
    }

    public function Save(){

        $this->GerarDbfs();

        $diretorio = $this->caminho;
        
        $zip = new ZipArchive();
        
        if($zip->open($diretorio.'AGREGARS.ZIP', ZIPARCHIVE::CREATE) == TRUE){
            
            foreach ($this->arquivos[0] as $key => $value) {
                //echo "{$value}";
                $zip->addFile($diretorio.$value,$value);
                
            }

            $zip->close();

            return $this->arquivos[0];
            
        }else{
            return array();
        }


    }

}

?>