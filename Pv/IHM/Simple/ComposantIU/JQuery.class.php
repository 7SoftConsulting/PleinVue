<?php
	
	if(! defined('PV_COMPOSANT_SIMPLE_JQUERY'))
	{
		if(! defined('PV_COMPOSANT_UI'))
		{
			include dirname(__FILE__)."/../../ComposantIU.class.php" ;
		}
		if(! defined('PV_NOYAU_SIMPLE_IHM'))
		{
			include dirname(__FILE__)."/../Noyau.class.php" ;
		}
		if(! defined('PV_FOURNISSEUR_DONNEES_SIMPLE'))
		{
			include dirname(__FILE__)."/../FournisseurDonnees.class.php" ;
		}
		define('PV_COMPOSANT_SIMPLE_JQUERY', 1) ;
		
		class PvFonctInstJQuery
		{
			public $NomMembreInst ;
			public $Args = array() ;
			public $Contenu = "" ;
			public function __construct($nomMembreInst, $args, $ctn='')
			{
				$this->NomMembreInst = $nomMembreInst ;
				$this->Args = $args ;
				$this->Contenu = $ctn ;
			}
			public function CtnJSDef()
			{
				$ctn = '' ;
				$ctn .= 'function ('.join(", ", $this->Args).') {
'.$this->Contenu.'
}' ;
				return $ctn ;
			}
		}
		
		class PvEditeurBaseJQuery extends PvEditeurHtmlBase
		{
			protected $CfgInst ;
			protected $FonctsInst = array() ;
			protected function CtnJSCfgInst()
			{
				$ctn = "" ;
				$ctn .= 'var cfgInst'.$this->IDInstanceCalc.' = '.svc_json_encode($this->CfgInst).' ;'."\n" ;
				foreach($this->FonctsInst as $i => $fonctInst)
				{
					if($fonctInst->Contenu == "")
					{
						continue ;
					}
					$ctn .= 'cfgInst'.$this->IDInstanceCalc.'.'.$fonctInst->NomMembreInst.' = '.$fonctInst->CtnJSDef().PHP_EOL ;
				}
				return $ctn ;
			}
			protected function CtnJSDeclInst()
			{
				$ctn = '' ;
				$ctn .= 'jQuery("#'.$this->IDInstanceCalc.'").find(cfgInst'.$this->IDInstanceCalc.') ;' ;
				return $ctn ;
			}
			protected function InitConfig()
			{
				parent::InitConfig() ;
				$this->CfgInst = $this->CreeCfgInst() ;
				$this->InitFonctsInst() ;
			}
			protected function CreeCfgInst()
			{
				return new StdClass() ;
			}
			protected function InitFonctsInst()
			{
			}
			protected function RenduDispositifBrut()
			{
				$ctn = parent::RenduDispositifBrut().PHP_EOL ;
				$ctn .= $this->RenduInstJS() ;
				return $ctn ;
			}
			protected function RenduInstJS()
			{
				$ctn = '' ;
				$ctn .= '<script type="text/javascript">
	jQuery(function() {
'.$this->CtnJSCfgInst().'
'.$this->CtnJSDeclInst().'
	}) ;
</script>' ;
				return $ctn ;
			}
		}
		
		class PvBarreMenuSuperfish extends PvBarreMenuWebBase
		{
			public static $SourceIncluse ;
			public static $CheminJs = "js/superfish.min.js" ;
			public static $CheminCSSPrinc = "css/superfish.css" ;
			public static $CheminCSSNavbar = "css/superfish-navbar.css" ;
			public static $CheminCSSVertical = "css/superfish-vertical.css" ;
			public $AppliquerJQueryUi = 0 ;
			public $CacherBordure = 0 ;
			protected function RenduSourceIncluse()
			{
				$ok = $this->ObtientValeurStatique("SourceIncluse") ;
				if($ok)
				{
					return '' ;
				}
				$ctn = '' ;
				$ctn .= '<script type="text/javascript" src="'.PvBarreMenuSuperfish::$CheminJs.'"></script>'.PHP_EOL ;
				$ctn .= '<link rel="stylesheet" type="text/css" href="'.PvBarreMenuSuperfish::$CheminCSSPrinc.'">'.PHP_EOL ;
				$ctn .= '<link rel="stylesheet" type="text/css" href="'.PvBarreMenuSuperfish::$CheminCSSNavbar.'">'.PHP_EOL ;
				$ctn .= '<link rel="stylesheet" type="text/css" href="'.PvBarreMenuSuperfish::$CheminCSSVertical.'">'.PHP_EOL ;
				$this->AffecteValeurStatique("SourceIncluse", 1) ;
				return $ctn ;
			}
			protected function RenduHabillageJQueryUi()
			{
				$ctn = '' ;
				$ctn .= '<script type="text/javascript">'.PHP_EOL ;
				$ctn .= 'jQuery(function () {
var currentMenu = jQuery("#'.$this->IDInstanceCalc.'") ;
currentMenu.find("li")
	.addClass("ui-state-default")
currentMenu.find("li a").hover(
		function () { jQuery(this).addClass("ui-state-active"); },
		function () { jQuery(this).removeClass("ui-state-active"); }
	);'.PHP_EOL ;
				if($this->CacherBordure)
				{
					$ctn .= 'currentMenu.find("li").css("border", "0px") ;'.PHP_EOL ;
				}
				$ctn .= '}) ;'.PHP_EOL ;
				$ctn .= '</script>' ;
				return $ctn ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= $this->RenduSourceIncluse() ;
				$ctn .= parent::RenduDispositifBrut() ;
				if($this->AppliquerJQueryUi)
				{
					$ctn .= PHP_EOL .$this->RenduHabillageJQueryUi() ;
				}
				return $ctn ;
			}
		}
		
		class PvConfigJQueryTreeview
		{
			public $persist = "location" ;
			public $collapsed = false ;
			public $unique = false ;
			public $cookieId = "" ;
		}
		class PvJQueryTreeview extends PvBarreMenuWebBase
		{
			protected static $SourceIncluse = 0 ;
			public $Config ;
			public $CheminCSS = "css/jquery.treeview.css" ;
			public $CheminJsJQueryCookie = "js/jquery.cookie.js" ;
			public $UtiliserJQueryCookie = 1 ;
			public $CheminJs = "js/jquery.treeview.js" ;
			public $AppliquerJQueryUi = 1 ;
			protected function InitConfig()
			{
				parent::InitConfig() ;
				$this->Config = new PvConfigJQueryTreeview() ;
			}
			protected function RenduSourceIncluse()
			{
				$sourceInc = $this->ObtientValeurStatique("SourceIncluse") ;
				if($sourceInc)
				{
					return "" ;
				}
				$ctn = '' ;
				$ctn .= $this->ZoneParent->RenduLienCSS($this->CheminCSS) ;
				$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJs) ;
				if($this->UtiliserJQueryCookie)
				{
					$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJsJQueryCookie) ;
				}
				$this->AffecteValeurStatique("SourceIncluse", 1) ;
				return $ctn ;
			}
			protected function RenduDefinitionJs()
			{
				$ctn = '' ;
				$ctn .= 'jQuery(function() {'.PHP_EOL ;
				$ctn .= 'var selection = jQuery("#'.$this->IDInstanceCalc.'") ;'.PHP_EOL ;
				if($this->AppliquerJQueryUi && $this->ZoneParent->InclureJQueryUi)
				{
					$ctn .= 'selection.addClass("ui-widget ui-state-default") ;'.PHP_EOL ;
					// $ctn .= 'selection.find("ul").css("background", "none") ;'.PHP_EOL ;
				}
				$ctn .= 'selection.treeview('.svc_json_encode($this->Config).') ;'.PHP_EOL ;
				$ctn .= '}) ;' ;
				return $ctn ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= $this->RenduSourceIncluse() ;
				$ctn .= parent::RenduDispositifBrut() ;
				$ctn .= $this->ZoneParent->RenduContenuJsInclus($this->RenduDefinitionJs()) ;
				return $ctn ;
			}
		
		}
		
		class PvLeftSlideBarJQuery extends PvComposantIUBase
		{
			protected $CheminJs = "js/slidebars.js" ;
			protected $CheminCSS = "css/slidebars.css" ;
			protected $NomClsCSSSlideBar = "sb-left" ;
			protected static $SourceIncluse = 0 ;
			// Doit etre initialis� avant la methode "ChargeConfig()"
			public $ComposantSupport ;
			public $LibelleLien = "Ouvrir le menu" ;
			protected function InitConfig()
			{
				parent::InitConfig() ;
				$this->ComposantSupport = new PvPortionRenduHtml() ;
			}
			public function & DeclareComposantSupport($comp)
			{
				$this->RemplaceCompSupport($comp) ;
				return $comp ;
			}
			public function & DeclareCompSupport($comp)
			{
				$this->RemplaceCompSupport($comp) ;
				return $comp ;
			}
			public function RemplaceComposantSupport(& $comp)
			{
				$this->ComposantSupport = & $comp ;
				if($this->EstPasNul($this->ScriptParent))
				{
					$this->ComposantSupport->AdopteScript('support_'.$this->NomElementScript, $this->ScriptParent) ;
				}
				if($this->EstPasNul($this->ZoneParent))
				{
					$this->ComposantSupport->AdopteZone('support_'.$this->NomElementZone, $this->ZoneParent) ;
				}
			}
			public function RemplaceCompSupport(& $comp)
			{
				$this->RemplaceComposantSupport($comp) ;
			}
			public function InsereCompSupport($comp)
			{
				$this->RemplaceComposantSupport($comp) ;
			}
			public function AdopteScript($nom, & $script)
			{
				parent::AdopteScript($nom, $script) ;
				if($this->EstPasNul($this->ComposantSupport))
				{
					$this->ComposantSupport->AdopteScript($nom.'_support', $script) ;
				}
			}
			public function AdopteZone($nom, & $zone)
			{
				parent::AdopteZone($nom, $zone) ;
				if($this->EstPasNul($this->ComposantSupport) && $this->EstNul($this->ScriptParent))
				{
					$this->ComposantSupport->AdopteZone($nom.'_support', $zone) ;
				}
			}
			protected function RenduSourceIncluse()
			{
				if($this->ObtientValeurStatique("SourceIncluse"))
				{
					return "" ;
				}
				$ctn = "" ;
				$ctn .= $this->ZoneParent->RenduLienCSS($this->CheminCSS) ;
				$ctn .= $this->ZoneParent->RenduContenuCSS('.sb-slidebar {
	padding: 14px;
	color: #fff;
}
html.sb-active #sb-site, .sb-toggle-left, .sb-toggle-right, .sb-open-left, .sb-open-right, .sb-close {
	cursor: pointer;
}
/* Fixed position examples */
#fixed-top {
	position: fixed;
	top: 0;
	width: 100%;
	height: 50px;
	background-color: red;
	z-index: 4;
}
#fixed-top span.sb-toggle-left {
	float: left;
	color: white;
	padding: 10px;
}
#fixed-top span.sb-toggle-right {
	float: right;
	color: white;
	padding: 10px;
}') ;
				$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJs) ;
				$ctn .= $this->ZoneParent->RenduContenuJsInclus($this->RenduDefinitionJs()) ;
				$this->AffecteValeurStatique("SourceIncluse", 1) ;
				return $ctn ;
			}
			protected function RenduDefinitionJs()
			{
				$ctn = '' ;
				$ctn .= '(function(jQuery) {
	jQuery(document).ready(function() {
		jQuery.slidebars();
	});
}) (jQuery);' ;
				return $ctn ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= $this->RenduSourceIncluse() ;
				$ctn .= '<div id="Lien'.$this->IDInstanceCalc.'"><a href="javascript:;" class="sb-open-left">'.$this->LibelleLien.'</a></div>'.PHP_EOL ;
				$ctn .= '<div id="'.$this->IDInstanceCalc.'" class="sb-slidebar '.$this->NomClsCSSSlideBar.'">'.PHP_EOL ;
				if($this->EstPasNul($this->ComposantSupport))
				{
					$ctn .= $this->ComposantSupport->RenduDispositif() ;
				}
				$ctn .= '</div>' ;
				return $ctn ;
			}
		}
		
		class PvConfigMaskMoney
		{
			public $prefix = "" ;
            public $suffix = "" ;
			public $affixesStay = true ;
			public $thousands = " " ;
			public $decimal = "" ;
			public $precision = 0 ;
			public $allowZero = false ;
			public $allowNegative = false ;
		}
		class PvMaskMoneyJQuery extends PvZoneInvisibleHtml
		{
			public static $SourceIncluse = 0 ;
			public $Config ;
			protected $ValeurEditeur ;
			public $CheminJs = "js/jquery.maskMoney.js" ;
			protected function InitConfig()
			{
				parent::InitConfig() ;
				$this->Config = new PvConfigMaskMoney() ;
			}
			public function InclutLibSource()
			{
				$ctn = '' ;
				if($this->ObtientValeurStatique('SourceIncluse') == 1)
				{
					return $ctn ;
				}
				$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJs) ;
				$this->AffecteValeurStatique("SourceIncluse", 1) ;
				return $ctn ;
			}
			protected function PrepareEditeur()
			{
				$this->ValeurEditeur = $this->Valeur ;
				if($this->Config->precision > 0 && intval($this->Valeur) != $this->Valeur)
				{
					$this->ValeurEditeur .= ".".str_repeat("0", $this->Config->precision) ;
				}
			}
			protected function RenduEditeur()
			{
				$ctn = '' ;
				$this->PrepareEditeur() ;
				$ctn .= '<input id="Editeur_'.$this->IDInstanceCalc.'"' ;
				$ctn .= ' value="'.htmlentities($this->ValeurEditeur).'"' ;
				$ctn .= ' type="text"' ;
				$ctn .= $this->RenduAttrStyleCSS() ;
				$ctn .= ' />' ;
				return $ctn ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= $this->InclutLibSource() ;
				$ctn .= $this->RenduEditeur() ;
				$ctn .= parent::RenduDispositifBrut() ;
				$ctn .= $this->ZoneParent->RenduContenuJsInclus('jQuery(function () {
	jQuery("#Editeur_'.$this->IDInstanceCalc.'").maskMoney('.svc_json_encode($this->Config).')
		.change(function () {
			if(jQuery(this).val() == "")
			{
				jQuery("#'.$this->IDInstanceCalc.'").val(jQuery(this).val()) ;
				return ;
			}
			var val = jQuery(this).maskMoney("unmasked") ;
			if(val[0] != undefined)
				val = val[0] ;'.(($this->Config->precision == 0) ? '
			alert(Math.pow(10, ((String(val).length > 2) ? 3 : String(val).length - 1))) ;
			val = val * Math.pow(10, ((String(val).length > 2) ? 3 : String(val).length - 1)) ;' : '').'
			jQuery("#'.$this->IDInstanceCalc.'").val(val) ;
		})
		.maskMoney("mask") ;
}) ;') ;
				return $ctn ;
			}
		}
		class PvConfigPriceFormat
		{
			public $prefix = "" ;
			public $suffix = "" ;
            public $centsSeparator = "." ;
			public $thousandsSeparator = " " ;
			public $limit = "" ;
			public $centsLimit = 3 ;
			public $clearPrefix = false ;
			public $allowNegative = false ;
			public $insertPlusSign = false ;
		}
		class PvPriceFormatJQuery extends PvZoneInvisibleHtml
		{
			public static $SourceIncluse = 0 ;
			public $Config ;
			protected $ValeurEditeur ;
			public $DelaiRafraichValeur = 0.5 ;
			public $CheminJs = "js/jquery.price_format.min.js" ;
			protected function CreeFmtLbl()
			{
				return new PvFmtMonnaie() ;
			}
			protected function InitConfig()
			{
				parent::InitConfig() ;
				$this->Config = new PvConfigPriceFormat() ;
			}
			public function InclutLibSource()
			{
				$ctn = '' ;
				if($this->ObtientValeurStatique('SourceIncluse') == 1)
				{
					return $ctn ;
				}
				$ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminJs) ;
				$this->AffecteValeurStatique("SourceIncluse", 1) ;
				return $ctn ;
			}
			protected function PrepareEditeur()
			{
				$this->ValeurEditeur = $this->Valeur ;
				if(empty($this->ValeurEditeur))
				{
					$this->ValeurEditeur = "0" ;
				}
				if($this->Config->centsLimit > 0)
				{
					if(strpos($this->ValeurEditeur, ".") === false)
					{
						$this->ValeurEditeur .= ".".str_repeat("0", $this->Config->centsLimit) ;
					}
					else
					{
						$partiesNb = explode(".", $this->ValeurEditeur, 2) ;
						if(strlen($partiesNb[1]) < $this->Config->centsLimit)
						{
							$this->ValeurEditeur .= str_repeat("0", $this->Config->centsLimit - strlen($partiesNb[1])) ;
						}
					}
				}
				// print $this->ValeurEditeur ;
			}
			protected function RenduEditeur()
			{
				$ctn = '' ;
				$this->PrepareEditeur() ;
				$ctn .= '<input id="Editeur_'.$this->IDInstanceCalc.'"' ;
				$ctn .= ' value="'.htmlentities($this->ValeurEditeur).'"' ;
				$ctn .= ' type="text"' ;
				$ctn .= $this->RenduAttrStyleCSS() ;
				$ctn .= ' />' ;
				return $ctn ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= $this->InclutLibSource() ;
				$ctn .= $this->RenduEditeur() ;
				$ctn .= parent::RenduDispositifBrut() ;
				$ctn .= $this->ZoneParent->RenduContenuJsInclus('function FixeValeur'.$this->IDInstanceCalc.'() {
	var maskValue = new String(jQuery("#Editeur_'.$this->IDInstanceCalc.'").val()) ;
	var result = "" ;
	for(var i=0; i<maskValue.length; i++)
	{
		var currentChar = maskValue.charAt(i) ;
		if(currentChar != '.svc_json_encode($this->Config->thousandsSeparator).')
		{
			if(currentChar == '.svc_json_encode($this->Config->centsSeparator).')
				currentChar = "." ;
			if(! isNaN(currentChar) || currentChar == "-" || currentChar == ".")
				result += currentChar ;
		}
	}
	jQuery("#'.$this->IDInstanceCalc.'").val(result) ;
	setTimeout("FixeValeur'.$this->IDInstanceCalc.'()", '.(1000 * $this->DelaiRafraichValeur).') ;
}
jQuery(function () {
	jQuery("#Editeur_'.$this->IDInstanceCalc.'").priceFormat('.svc_json_encode($this->Config).') ;
	FixeValeur'.$this->IDInstanceCalc.'() ;
})') ;
				return $ctn ;
			}
		}
        
        class ResultTypeahead
        {
            public $total_pages = 0 ;
            public $total_results = 0 ;
            public $page ;
            public $results = array() ;
        }
        class PvActEnvoiResultsTypeahead extends PvActionResultatJSONZoneWeb
        {
            /**
            * Composant Typeahead qui contient l'action
            *
            * @var PvTypeahead
            */
            public $ComposantRendu ;
            protected function ObtientFiltresSelection()
            {
                $filtres = $this->ComposantRendu->FiltresSelection ;
                $filtres["terme"] = $this->ScriptParent->CreeFiltreHttpGet($this->ComposantRendu->IDInstanceCalc."_terme") ;
                if(isset($this->ComposantRendu->FournisseurDonnees->BaseDonnees))
                {
                    $bd = & $this->ComposantRendu->FournisseurDonnees->BaseDonnees ;
                    $filtres["terme"]->ExpressionDonnees = $bd->SqlIndexOf('lower('.$bd->EscapeVariableName($this->ComposantRendu->NomColonneLibelle).')', 'upper(<self>)').' > 0' ;
                }
                // $filtres[] = $this->Sc
                return $filtres ;
            }
            protected function ObtientColonnesRendu()
            {
                $cols = array() ;
                $col1 = new PvDefinitionColonneDonnees() ;
                $col1->NomDonnees = "value" ;
                $col1->AliasDonnees = $this->ComposantRendu->NomColonneValeur ;
                $cols[] = $col1 ;
                $col2 = new PvDefinitionColonneDonnees() ;
                $col2->NomDonnees = "label" ;
                $col2->AliasDonnees = $this->ComposantRendu->NomColonneLibelle ;
                $cols[] = $col2 ;
                return $cols ;
            }
            protected function ConstruitResultat() {
                $result = new ResultTypeahead() ;
                if(is_array($result->results))
                {
                    $result->results = $this->ComposantRendu->FournisseurDonnees->SelectElements($this->ObtientColonnesRendu(), $this->ObtientFiltresSelection()) ;
                    $result->page = 1 ;
                    $result->total_pages = 1 ;
                    $result->total_results = count($result->results) ;
                }
                $this->Resultat = $result ;
            }
            
        }
        class PvTypeahead extends PvEditeurChoixBase
        {
            public $CheminFichierJs = "js/typeahead.bundle.min.js" ;
			public $LibelleEtiqVide = "" ;
            /**
            * Action generant les resultats JSON
            *
            * @var PvActEnvoiResultsTypeahead
            */
            public $ActEnvoiResults ;
            public $ParamsActEnvoiResults = array() ;
            public function AdopteScript($nom, & $script)
            {
                $this->ActEnvoiResults = new PvActEnvoiResultsTypeahead() ;
                $script->InscritActionAvantRendu($this->IDInstanceCalc.'_results', $this->ActEnvoiResults) ;
                $this->ActEnvoiResults->ComposantRendu = & $this ;
                parent::AdopteScript($nom, $script);
            }
            protected function RenduSourceBrut() {
                $ctn = '' ;
                $ctn .= $this->ZoneParent->RenduLienJsInclus($this->CheminFichierJs) ;
                $ctn .= $this->ZoneParent->RenduContenuCSS('.tt-query, .tt-hint {
	width: 396px;
	height: 30px;
	padding: 8px 12px;
	font-size: 24px;
	line-height: 30px;
	border: 2px solid #ccc;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	display:none ;
}
.tt-query {
	-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
	box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
.tt-hint {
	color: #999
}
.tt-menu {
	width: 422px;
	margin: 2px 0;
	padding: 8px 0;
	background-color: #fff;
	border: 1px solid #ccc;
	border: 1px solid rgba(0, 0, 0, 0.2);
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	-webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
	-moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
	box-shadow: 0 5px 10px rgba(0,0,0,.2);
}
.tt-suggestion {
	padding: 3px 20px;
	font-size: 18px;
	line-height: 24px;
}
.tt-suggestion:hover {
	cursor: pointer;
	color: #fff;
	background-color: #0097cf;
}
.tt-suggestion.tt-cursor {
	color: #fff;
	background-color: #0097cf;
}
.tt-suggestion p {
	margin: 0;
}') ;
                return $ctn ;
            }
            protected function RenduEditeurBrut() {
                $this->ActEnvoiResults->Params = $this->ParamsActEnvoiResults ;
                $ctn = '' ;
                $ctn .= '<input class="typeahead" id="'.$this->IDInstanceCalc.'_libelle" type="text" placeholder="" value="'.htmlentities($this->RenduEtiquette()).'" />'.PHP_EOL ;
                $ctn .= '<input type="hidden" name="'.htmlentities($this->NomElementHtml).'" id="'.$this->IDInstanceCalc.'" value="'.htmlentities($this->Valeur).'" />'.PHP_EOL ;
                $ctn .= $this->ZoneParent->RenduContenuJsInclus('var '.$this->IDInstanceCalc.'_dataset = new Bloodhound({
	datumTokenizer: function (datum) {
		return Bloodhound.tokenizers.whitespace(datum.title);
	},
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	remote: {
		url: '.svc_json_encode($this->ActEnvoiResults->ObtientUrl()).',
		filter: function (dataset) {
            // Map the remote source JSON array to a JavaScript object array
            return jQuery.map(dataset.results, function (record) {
                return record ;
            });
        },
        replace: function(url, uriEncodedQuery) {
            return url + "&'.$this->IDInstanceCalc.'_terme=" + uriEncodedQuery;
        }
	}
});
// Initialize the Bloodhound suggestion engine
'.$this->IDInstanceCalc.'_dataset.initialize();
// Instantiate the Typeahead UI
jQuery("#'.$this->IDInstanceCalc.'_libelle").typeahead(null, {
	displayKey: "label",
	valueKey: "value",
	limit: 12,
	source: '.$this->IDInstanceCalc.'_dataset.ttAdapter()
});
jQuery("#'.$this->IDInstanceCalc.'_libelle").on("typeahead:selected typeahead:autocompleted", function(e,datum) {
	jQuery("#'.$this->IDInstanceCalc.'").val(datum.value);
}) ;') ;
                return $ctn ;
            }
        }
		
		class PvSelect2 extends PvEditeurBaseJQuery
		{
			public $CheminFichierJs = "js/select2.min.js" ;
			public $CheminFichierCSS = "css/select2.min.css" ;
			public $FournisseurDonnees ;
			public $NomColonneLibelle ;
			public $NomColonneValeur ;
			public $ActSupport ;
			public $Largeur = '200px' ;
			public $MaxElemsParPage = 30 ;
			public $FiltresSelection = array() ;
			public function ExtraitFiltresSelection($termeRech)
			{
				$filtres = $this->FiltresSelection ;
				return $filtres ;
			}
			public function InitConfig()
			{
				parent::InitConfig() ;
				$this->ActSupport = new PvActSupportSelect2() ;
			}
			public function AdopteZone($nom, & $zone)
			{
				parent::AdopteZone($nom, $zone) ;
				$this->InscritActionAvantRendu("ActSupport_".$this->IDInstanceCalc, $this->ActSupport) ;
				$this->CfgInst->ajax->url = $this->ActSupport->ObtientUrl() ;
			}
			public function ChargeConfig()
			{
				parent::ChargeConfig() ;
				$this->ActSupport->ChargeConfig() ;
			}
			protected function InitFonctsInst()
			{
				$this->FonctsInst[] = new PvFonctInstJQuery("escapeMarkup", array("markup"), "return markup ;") ;
				$this->FonctsInst[] = new PvFonctInstJQuery("ajax.data", array("params"), "return {
	q: params.term,
	page: params.page
} ;") ;
			}
			protected function RenduEditeurBrut()
			{
				$ctn = '' ;
				$ctn .= '<select name="'.htmlentities($this->NomElementHtml).'" id="'.$this->IDInstanceCalc.'"'.$this->RenduAttrStyleCSS().'>'.PHP_EOL ;
				$ctn .= '</select>' ;
				return $ctn ;
			}
			protected function CreeCfgInst()
			{
				return new PvCfgSelect2() ;
			}
			protected function CtnJSDeclInst()
			{
				$ctn = 'jQuery("#'.$this->IDInstanceCalc.'").select2(cfgInst'.$this->IDInstanceCalc.') ;' ;
				return $ctn ;
			}
			protected function RenduSourceBrut()
			{
				$this->FonctsInst[] = new PvFonctInstJQuery("ajax.processResults", array("data", "params"), 'params.page = params.page || 1;
return {
	results: jQuery.map(data.items, function (item) {
		return {
			text: item.'.$this->NomColonneLibelle.',
			id: item.'.$this->NomColonneValeur.'
		};
	}),
	pagination: {
		more: (params.page * '.$this->MaxElemsParPage.') < data.total_count
	}
};') ;
				return '<script type="text/javascript" src="'.$this->CheminFichierJs.'"></script>
<link rel="stylesheet" type="text/css" href="'.$this->CheminFichierCSS.'">'.PHP_EOL ;
			}
		}
		class PvActSupportSelect2 extends PvActionResultatJSONZoneWeb
		{
			protected $TermeRech ;
			protected function ConstruitResultat()
			{
				$comp = & $this->ComposantIUParent ;
				$fourn = & $this->ComposantIUParent->FournisseurDonnees ;
				$this->Resultat = new PvResultSelect2() ;
				// print "hh : ".get_class($comp) ;
				if($this->EstNul($comp) || $comp->EstNul($fourn))
				{
					return ;
				}
				$this->TermeRech = (isset($_GET["q"])) ? $_GET["q"] : '' ;
				$filtres = $comp->ExtraitFiltresSelection($this->TermeRech) ;
				// $this->Resultat->total_count = $fourn->CompteElements(array(), $filtres) ;
				$this->Resultat->items = $fourn->RechDebuteElements($filtres, array($comp->NomColonneLibelle, $comp->NomColonneValeur), $this->TermeRech) ;
				$this->Resultat->total_count = count($this->Resultat->items) ;
				// print_r($fourn) ;
			}
		}
		class PvResultSelect2
		{
			public $items = array() ;
			public $total_count = 0 ;
		}
		class PvCfgSelect2
		{
			public $ajax ;
			public $escapeMarkup ;
			public $minimumInputLength = 1 ;
			public $placeholder = "" ;
			public $allowClear = true ;
			public $data = true ;
			public $tags = false ;
			public $tokenSeparators = array() ;
			public function __construct()
			{
				$this->ajax = new PvCfgAjaxSelect2() ;
			}
		}
		class PvCfgAjaxSelect2
		{
			public $url ;
			public $dataType = "json" ;
			public $type = "GET" ;
			public $delay = 250 ;
			public $data ;
			public $processResults ;
			public $cache = true ;
		}
		
		class PvJQueryLightbox extends PvComposantIUBase
		{
			public static $CheminFichierJs = "js/lightbox.min.js" ;
			public static $CheminFichierCSS = "css/lightbox.min.css" ;
			public static $SourceIncluse = 0 ;
			public $NomColCheminImage = "chemin_image" ;
			public $NomColCheminMiniature = "chemin_miniature" ;
			public $NomColTitre = "titre" ;
			public $FournisseurDonnees = null ;
			public $LargeurMiniature = 60 ;
			public $FiltresSelection = array() ;
			public $MsgPreRequisNonVerif = "Pr&eacute;requis non v&eacute;rifi&eacute;s : fournisseur de donn&eacute;es non configur&eacute;" ;
			protected static function InclutLibSource()
			{
				if(PvJQueryLightbox::$SourceIncluse == 1)
				{
					return '' ;
				}
				$ctn = '' ;
				$ctn .= '<link rel="stylesheet" href="'.PvJQueryLightbox::$CheminFichierCSS.'">'.PHP_EOL ;
				$ctn .= '<script type="text/javascript" src="'.PvJQueryLightbox::$CheminFichierJs.'"></script>'.PHP_EOL ;
				return $ctn ;
			}
			protected function VerifiePreRequis()
			{
				if($this->EstNul($this->FournisseurDonnees))
				{
					return 0 ;
				}
				if($this->FournisseurDonnees->RequeteSelection == "")
				{
					return 0 ;
				}
				return 1 ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= PvJQueryLightbox::InclutLibSource() ;
				$ctn .= '<div id="'.$this->IDInstanceCalc.'">'.PHP_EOL ;
				if($this->VerifiePreRequis() == 0)
				{
					$ctn .= '<div class="Erreur">'.$this->MsgPreRequisNonVerif.'</div>' ;
				}
				else
				{
					$fourn = & $this->FournisseurDonnees ;
					$requeteSupport = $fourn->OuvreRequeteSelectElements($this->FiltresSelection) ;
					while($lgn = $fourn->LitRequete($requeteSupport))
					{
						$cheminImage = $lgn[$this->NomColCheminImage] ;
						$cheminMiniature = $cheminImage ;
						$titre = "" ;
						if(isset($lgn[$this->NomColCheminMiniature]))
						{
							$cheminMiniature = $lgn[$this->NomColCheminMiniature] ;
						}
						if(isset($lgn[$this->NomColTitre]))
						{
							$titre = $lgn[$this->NomColTitre] ;
						}
						$ctn .= '<a href="'.htmlspecialchars($cheminImage).'" data-lightbox="lightbox-'.$this->IDInstanceCalc.'" data-title="'.htmlspecialchars($titre).'"><img src="'.htmlspecialchars($cheminMiniature).'" alt="'.htmlspecialchars($titre).'" border="0" width="'.$this->LargeurMiniature.'" /></a>'.PHP_EOL ;
					}
					$fourn->FermeRequete($requeteSupport) ;
				}
				$ctn .= '</div>'.PHP_EOL ;
				return $ctn ;
			}
		}
		
		class PvCfgJQuerySnowfall
		{
			public $flakeCount = 35 ;
			public $flakeColor = "#ffffff" ;
			public $flakePosition= 'absolute';
			public $flakeIndex= 999999 ;
			public $minSize = 1 ;
			public $maxSize = 2 ;
			public $minSpeed = 1 ;
			public $maxSpeed = 5 ;
			public $round = false ;
			public $shadow = false ;
			public $collection = false ;
			public $collectionHeight = 40 ;
			public $deviceorientation = false ;
		}
		class PvJQuerySnowfall extends PvComposantIUBase
		{
			public static $CheminFichierJs = "js/snowfall.jquery.min.js" ;
			public static $SourceIncluse = 0 ;
			public $Cfg = null ;
			protected function InitConfig()
			{
				parent::InitConfig() ;
				$this->Cfg = new PvCfgJQuerySnowfall() ;
			}
			protected static function InclutLibSource()
			{
				if(PvJQuerySnowfall::$SourceIncluse == 1)
				{
					return '' ;
				}
				$ctn = '' ;
				$ctn .= '<script type="text/javascript" src="'.PvJQuerySnowfall::$CheminFichierJs.'"></script>'.PHP_EOL ;
				return $ctn ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= PvJQuerySnowfall::InclutLibSource() ;
				$ctn .= '<script type="text/javascript">
	jQuery(document).snowfall('.svc_json_encode($this->Cfg).') ;
</script>'.PHP_EOL ;
				return $ctn ;
			}
		}
	}
	
?>