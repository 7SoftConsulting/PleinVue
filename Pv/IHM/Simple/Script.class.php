<?php
	
	if(! defined('PV_SCRIPT_SIMPLE_IHM'))
	{
		if(! defined('PV_NOYAU_SIMPLE_IHM'))
		{
			include dirname(__FILE__)."/Noyau.class.php" ;
		}
		if(! defined('PV_COMPOSANT_IU_SIMPLE'))
		{
			include dirname(__FILE__)."/Composant.class.php" ;
		}
		if(! defined('PV_FOURNISSEUR_DONNEES_SIMPLE'))
		{
			include dirname(__FILE__)."/FournisseurDonnees.class.php" ;
		}
		define('PV_SCRIPT_SIMPLE_IHM', 1) ;
		
		class PvScriptWebSimple extends PvScriptIHMDeBase
		{
			public $Titre ;
			public $TitreDocument ;
			public $MotsCleMeta ;
			public $DescriptionMeta ;
			public $ViewportMeta ;
			public $AuteurMeta ;
			public $Chemin = array("") ;
			public $Description = "" ;
			public $ComposantSpecifique = null ;
			public $Composant1 = null ;
			public $Composant2 = null ;
			public $Composant3 = null ;
			public $DetectIconeCorresp = 0 ;
			public $CheminIcone = null ;
			public $UrlsReferantsSurs = array() ;
			public $HotesReferantsSurs = array() ;
			public $RefererHoteLocal = 0 ;
			public $RefererUrlLocale = 0 ;
			public $ScriptsReferantsSurs = array() ;
			public $RefererScriptLocal = 0 ;
			public $UtiliserCorpsDocZone = 1 ;
			public function EstBienRefere()
			{
				return PvVerificateurReferantsSursWeb::Valide($this) ;
			}
			public function InscritActionAvantRendu($nomAction, & $action)
			{
				$this->ZoneParent->ActionsAvantRendu[$nomAction] = & $action ;
				$action->AdopteScript($nomAction, $this) ;
			}
			public function InscritActionApresRendu($nomAction, & $action)
			{
				$this->ZoneParent->ActionsApresRendu[$nomAction] = & $action ;
				$action->AdopteScript($nomAction, $this) ;
			}
			public function & CreeFiltreRef($nom, & $filtreRef)
			{
				$filtre = new PvFiltreDonneesRef() ;
				$filtre->Source = & $filtreRef ;
				$filtre->AdopteScript($nom, $this) ;
				return $filtre ;
			}
			public function & CreeFiltreFixe($nom, $valeur)
			{
				$filtre = new PvFiltreDonneesFixe() ;
				$filtre->ValeurParDefaut = $valeur ;
				$filtre->AdopteScript($nom, $this) ;
				return $filtre ;
			}
			public function & CreeFiltreCookie($nom)
			{
				$filtre = new PvFiltreDonneesCookie() ;
				$filtre->AdopteScript($nom, $this) ;
				return $filtre ;
			}
			public function & CreeFiltreSession($nom)
			{
				$filtre = new PvFiltreDonneesSession() ;
				$filtre->AdopteScript($nom, $this) ;
				return $filtre ;
			}
			public function & CreeFiltreMembreConnecte($nom, $nomParamLie='')
			{
				$filtre = new PvFiltreDonneesMembreConnecte() ;
				$filtre->AdopteScript($nom, $this) ;
				$filtre->NomParametreLie = $nomParamLie ;
				return $filtre ;
			}
			public function & CreeFiltreHttpUpload($nom, $cheminDossierDest="")
			{
				$filtre = new PvFiltreDonneesHttpUpload() ;
				$filtre->AdopteScript($nom, $this) ;
				$filtre->CheminDossier = $cheminDossierDest ;
				return $filtre ;
			}
			public function & CreeFiltreHttpGet($nom)
			{
				$filtre = new PvFiltreDonneesHttpGet() ;
				$filtre->AdopteScript($nom, $this) ;
				return $filtre ;
			}
			public function & CreeFiltreHttpPost($nom)
			{
				$filtre = new PvFiltreDonneesHttpPost() ;
				$filtre->AdopteScript($nom, $this) ;
				return $filtre ;
			}
			public function & CreeFiltreHttpRequest($nom)
			{
				$filtre = new PvFiltreDonneesHttpRequest() ;
				$filtre->AdopteScript($nom, $this) ;
				return $filtre ;
			}
			public function PrepareRendu()
			{
			}
			public function RenduTitre()
			{
				if(! $this->ZoneParent->InclureRenduTitre)
				{
					return '' ;
				}
				$ctn = '' ;
				$ctn .= '<div class="titre">' ;
				if($this->ZoneParent->InclureRenduIcone)
				{
					$cheminIcone = ($this->CheminIcone != '' && file_exists($this->CheminIcone)) ? $this->CheminIcone : $this->ZoneParent->CheminIconeScript ;
					if($cheminIcone != '')
					{
						$ctn .= '<img src="'.$cheminIcone.'" height="22" />&nbsp;&nbsp;' ;
					}
				}
				$ctn .= $this->Titre ;
				$ctn .= '</div>' ;
				return $ctn ;
			}
			public function ObtientTitreDocument()
			{
				return $this->TitreDocument ;
			}
			public function RenduChemin()
			{
				if(! $this->ZoneParent->InclureRenduChemin)
				{
					return '' ;
				}
			}
			public function RenduDescription()
			{
				if(! $this->ZoneParent->InclureRenduDescription)
				{
					return '' ;
				}
			}
			public function RenduSpecifique()
			{
			}
			public function RenduComposant1()
			{
			}
			public function RenduComposant2()
			{
			}
			public function RenduComposant3()
			{
			}
			public function RenduDispositif()
			{
				return $this->RenduDispositifBrut() ;
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				$ctn .= $this->RenduChemin().PHP_EOL ;
				$ctn .= $this->RenduTitre().PHP_EOL ;
				$ctn .= $this->RenduDescription().PHP_EOL ;
				$ctn .= $this->RenduSpecifique() ;
				return $ctn ;
			}
			public function ObtientUrl()
			{
				return $this->ZoneParent->ObtientUrlScript($this->NomElementZone) ;
			}
			public function ObtientUrlParam($params=array())
			{
				return $this->ZoneParent->ObtientUrlScript($this->NomElementZone, $params) ;
			}
			public function ObtientUrlFmt($params=array(), $autresParams=array())
			{
				$url = $this->ZoneParent->ObtientUrlScript($this->NomElementZone, $autresParams) ;
				foreach($params as $nom => $val)
				{
					$url .= '&'.urlencode($nom).'='.$val ;
				}
				return $url ;
			}
			public function AdopteZone($nom, & $zone)
			{
				parent::AdopteZone($nom, $zone) ;
				if($this->DetectIconeCorresp || $this->ZoneParent->DetectIconeCorresp)
				{
					$cheminIcone = $this->ZoneParent->CheminDossierIconeCorresp.'/'.$this->NomElementZone.'.'.$this->ZoneParent->ExtIconeCorresp ;
					if($this->CheminIcone == '' && file_exists($cheminIcone))
					{
						$this->CheminIcone = $cheminIcone ;
					}
				}
			}
		}
		
		class PvScriptWebDonneesBase extends PvScriptWebSimple
		{
		}
		class PvFormulaireWebDonneesSimple extends PvScriptWebDonneesBase
		{
			public $NomClasseFormulaireDonnees = "PvFormulaireDonneesHtml" ;
			public $ComposantFormulaireDonnees = null ;
			public $InitComposantEnvironnement = 1 ;
			public function DetermineEnvironnement()
			{
				if($this->InitComposantEnvironnement)
				{
					$this->InitComposantFormulaireDonnees() ;
					if($this->EstPasNul($this->ComposantFormulaireDonnees))
						$this->ChargeConfigComposantFormulaireDonnees() ;
				}
			}
			protected function InitComposantFormulaireDonnees()
			{
				$nomClasse = $this->NomClasseFormulaireDonnees ;
				if(class_exists($nomClasse))
				{
					$this->ComposantFormulaireDonnees = new $nomClasse() ;
					$this->ComposantFormulaireDonnees->AdopteScript("specifique", $this) ;
					// print get_class($this->ComposantFormulaireDonnees->ScriptParent) ;
				}
				else
				{
					$this->ComposantFormulaireDonnees = null ;
				}
			}
			protected function ChargeConfigComposantFormulaireDonnees()
			{
				$this->ComposantFormulaireDonnees->ChargeConfig() ;
			}
			public function RenduSpecifique()
			{
				if(! $this->InitComposantEnvironnement)
				{
					$this->InitComposantFormulaireDonnees() ;
					if(! $this->EstNul($this->ComposantFormulaireDonnees))
					{
						$this->ChargeConfigComposantFormulaireDonnees() ;
					}
				}
				if(! $this->EstNul($this->ComposantFormulaireDonnees))
				{
					return $this->ComposantFormulaireDonnees->RenduDispositif() ;
				}
				else
				{
					return '-- composant non initialise --' ;
				}
			}
		}
		class PvFormulaireAjoutDonneesSimple extends PvFormulaireWebDonneesSimple
		{
			public $NomClasseFormulaireDonnees = "PvFormulaireAjoutDonneesHtml" ;
		}
		class PvFormulaireModifDonneesSimple extends PvFormulaireWebDonneesSimple
		{
			public $NomClasseFormulaireDonnees = "PvFormulaireModifDonneesHtml" ;
		}
		class PvFormulaireSupprDonneesSimple extends PvFormulaireWebDonneesSimple
		{
			public $NomClasseFormulaireDonnees = "PvFormulaireSupprDonneesHtml" ;
		}
		
		class PvScriptTableauDonneesSimple extends PvScriptWebDonneesBase
		{
			public $NomClasseTableauDonnees = "PvTableauDonneesHtml" ;
			public $ComposantTableauDonnees = null ;
			public $InitComposantEnvironnement = 1 ;
			public function DetermineEnvironnement()
			{
				if($this->InitComposantEnvironnement)
				{
					$this->InitComposantTableauDonnees() ;
					if($this->EstPasNul($this->ComposantTableauDonnees))
						$this->ChargeConfigComposantTableauDonnees() ;
				}
			}
			protected function InitComposantTableauDonnees()
			{
				$nomClasse = $this->NomClasseTableauDonnees ;
				if(class_exists($nomClasse))
				{
					$this->ComposantTableauDonnees = new $nomClasse() ;
					$this->ComposantTableauDonnees->AdopteScript("specifique", $this) ;
				}
				else
				{
					$this->ComposantTableauDonnees = null ;
				}
			}
			protected function ChargeConfigComposantTableauDonnees()
			{
				$this->ComposantTableauDonnees->ChargeConfig() ;
			}
			public function RenduSpecifique()
			{
				if($this->InitComposantEnvironnement == 0)
				{
					$this->InitComposantTableauDonnees() ;
				}
				if(! $this->EstNul($this->ComposantTableauDonnees))
				{
					if($this->InitComposantEnvironnement == 0)
					{
						$this->ChargeConfigComposantTableauDonnees() ;
					}
					return $this->ComposantTableauDonnees->RenduDispositif() ;
				}
				else
				{
					return '-- composant non initialise --' ;
				}
			}
		}
		
		class PvScriptConnexionWeb extends PvScriptWebSimple
		{
			public $Titre = "Connexion" ;
			public $TitreDocument = "Connexion" ;
			public $LibellePseudo = "Nom d'utilisateur" ;
			public $NomParamPseudo = "pseudo" ;
			public $ValeurParamPseudo = "" ;
			public $LibelleMotPasse = "Mot de passe" ;
			public $NomParamMotPasse = "motDePasse" ;
			public $ValeurParamMotPasse = "" ;
			public $NomParamSoumetTentative = "tentativeConnexion" ;
			public $ValeurParamSoumetTentative = 1 ;
			public $TentativeConnexionEnCours = 0 ;
			public $TentativeConnexionValidee = 0 ;
			public $UrlConnexionReussie = "" ;
			public $UrlConnexionEchouee = "" ;
			public $NomScriptConnexionReussie = "accueil" ;
			public $NomScriptConnexionEchouee = "" ;
			public $MessageConnexionReussie = 'Bienvenue, ${PSEUDO}. Vous vous �tes connect� avec succ�s' ;
			public $IdMembre = -1 ;
			public $NecessiteMembreConnecte = 0 ;
			public $AfficherBoutonSoumettre = 1 ;
			public $AfficherMessageErreur = 1 ;
			public $LibelleBoutonSoumettre = "Se connecter" ;
			public $MessageConnexionEchouee = "" ;
			public $MessageErreurValidation = "Nom d'utilisateur / Mot de passe invalide." ;
			public $UtiliserMessageExplicite = 1 ;
			public $MessageMotPasseIncorrect = "Le mot de passe est incorrect" ;
			public $MessageMembreNonTrouve = "Utilisateur non trouv&eacute;" ;
			public $MessageMembreNonActif = "Votre compte a &eacute;t&eacute; d&eacute;sactiv&eacute;" ;
			public $MessageAuthADEchoue = "Echec de l'authentification sur le domaine" ;
			public $MessagesErreurValidation = array() ;
			protected function DetecteTentativeConnexion()
			{
				$this->TentativeConnexionEnCours = 0 ;
				if(isset($_POST[$this->NomParamSoumetTentative]))
				{
					$this->TentativeConnexionEnCours = ($_POST[$this->NomParamSoumetTentative] == $this->ValeurParamSoumetTentative) ? 1 : 0 ;
					$this->ValeurParamPseudo = (isset($_POST[$this->NomParamPseudo])) ? $_POST[$this->NomParamPseudo] : "" ;
					$this->ValeurParamMotPasse = (isset($_POST[$this->NomParamMotPasse])) ? $_POST[$this->NomParamMotPasse] : "" ;
				}
			}
			protected function ValideTentativeConnexion()
			{
				return 1 ;
			}
			public function DetermineEnvironnement()
			{
				$this->DetecteTentativeConnexion() ;
				$this->IdMembre = -1 ;
				$this->TentativeConnexionValidee = 0 ;
				if($this->TentativeConnexionEnCours && ! $this->ZoneParent->EstNul($this->ZoneParent->Membership) && $this->ValideTentativeConnexion())
				{
					$this->IdMembre = $this->ZoneParent->Membership->ValidateConnection($this->ValeurParamPseudo, $this->ValeurParamMotPasse) ;
					$this->TentativeConnexionValidee = ($this->IdMembre != $this->ZoneParent->Membership->IdMemberNotFoundValue) ? 1 : 0 ;
				}
				// print_r($this->ZoneParent->Membership->Database) ;
				// print_r($this->IdMembre.' jjj') ;
				// exit ;
				if($this->TentativeConnexionValidee == 1)
				{
					$this->ZoneParent->Membership->LogonMember($this->IdMembre) ;
					$url = '' ;
					if($this->NomScriptConnexionReussie != '' && isset($this->ZoneParent->Scripts[$this->NomScriptConnexionReussie]))
					{
						$url = $this->ZoneParent->Scripts[$this->NomScriptConnexionReussie]->ObtientUrl() ;
					}
					elseif($this->UrlConnexionReussie != '')
					{
						$url = $this->UrlConnexionReussie ;
					}
					if($url != '')
					{
						redirect_to($url) ;
					}
				}
				else
				{
					$url = '' ;
					if($this->NomScriptConnexionEchouee != '' && isset($this->ZoneParent->Scripts[$this->NomScriptConnexionEchouee]))
					{
						$url = $this->ZoneParent->Scripts[$this->NomScriptConnexionEchouee]->ObtientUrl() ;
					}
					elseif($this->UrlConnexionEchouee != '')
					{
						$url = $this->UrlConnexionEchouee ;
					}
					if($url != '')
					{
						$url = update_url_params(array('connexionEchouee' => 1)) ;
						redirect_to($url) ;
					}
					elseif($this->UtiliserMessageExplicite)
					{
						$this->MessageConnexionEchouee = $this->MessageErreurValidation ;
						switch($this->ZoneParent->Membership->LastValidateError)
						{
							case AkSqlMembership::VALIDATE_ERROR_MEMBER_NOT_FOUND :
							{
								$this->MessageConnexionEchouee = $this->MessageMembreNonTrouve ;
							}
							break ;
							case AkSqlMembership::VALIDATE_ERROR_MEMBER_NOT_ENABLED :
							{
								$this->MessageConnexionEchouee = $this->MessageMembreNonActif ;
							}
							break ;
							case AkSqlMembership::VALIDATE_ERROR_PASSWORD_INCORRECT :
							{
								$this->MessageConnexionEchouee = $this->MessageMotPasseIncorrect ;
							}
							break ;
							case AkSqlMembership::VALIDATE_ERROR_AD_AUTH_FAILED :
							{
								$this->MessageConnexionEchouee = $this->MessageAuthADEchoue ;
							}
							break ;
							case AkSqlMembership::VALIDATE_ERROR_AD_AUTH_FAILED :
							{
								$this->MessageConnexionEchouee = $this->MessageAuthADEchoue ;
							}
							break ;
							default :
							{
								if(isset($this->MessagesErreurValidation[$this->ZoneParent->Membership->LastValidateError]))
								{
									$this->MessageConnexionEchouee = $this->MessagesErreurValidation[$this->ZoneParent->Membership->LastValidateError] ;
								}
							}
							break ;
						}
					}
					else
					{
						$this->MessageConnexionEchouee = $this->MessageErreurValidation ;
					}
				}
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				if($this->TentativeConnexionValidee)
				{
					$message = _parse_pattern(
						$this->MessageConnexionReussie,
						array(
							"PSEUDO" => $this->ValeurParamPseudo
						)
					) ;
					$ctn .= '<p>'.htmlentities($message).'</p>' ;
				}
				else
				{
					$ctn .= parent::RenduDispositifBrut() ;
				}
				return $ctn ;
			}
			public function RenduSpecifique()
			{
				$ctn = '' ;
				$ctn .= '<form action="'.$this->ObtientUrl().'" method="post">'.PHP_EOL ;
				$ctn .= '<div align="center">'.PHP_EOL ;
				if($this->TentativeConnexionEnCours && $this->TentativeConnexionValidee == 0)
				{
					$ctn .= '<div class="erreur">'.$this->MessageConnexionEchouee.'</div>'.PHP_EOL ;
				}
				$ctn .= $this->RenduTableauParametres().PHP_EOL ;
				if($this->AfficherBoutonSoumettre)
				{
					$ctn .= '<p align="center"><input type="submit" value="'.$this->LibelleBoutonSoumettre.'" /></p>'.PHP_EOL ;
				}
				$ctn .= '</div>' ;
				$ctn .= '</form>' ;
				return $ctn ;
			}
			public function RenduTableauParametres()
			{
				$ctn = '' ;
				$ctn .= '<table align="center" cellspacing="0" cellpadding="4">
			<tr>
				<td width="50%" align="left">
					<label for="'.$this->NomParamPseudo.'">'.$this->LibellePseudo.'</label>
				</td>
				<td width="*" align="left">
					<input type="text" name="'.$this->NomParamPseudo.'" id="'.$this->NomParamPseudo.'" value="'.htmlentities($this->ValeurParamPseudo).'" />
				</td>
			</tr>
			<tr>
				<td align="left">
					<label for="'.$this->NomParamMotPasse.'">'.$this->LibelleMotPasse.'</label>
				</td>
				<td align="left">
					<input type="password" name="'.$this->NomParamMotPasse.'" id="'.$this->NomParamMotPasse.'" value="" />
				</td>
			</tr>
		</table>
		<input type="hidden" name="'.$this->NomParamSoumetTentative.'" value="'.htmlentities($this->ValeurParamSoumetTentative).'" />' ;
				return $ctn ;
			}
		}
		class PvScriptRecouvreMPWeb extends PvFormulaireWebDonneesSimple
		{
			public $Titre = "Mot de passe oubli&eacute;" ;
			public $TitreDocument = "Mot de passe oubli&eacute;" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireRecouvreMPMS" ;
		}
		class PvScriptDeconnexionWeb extends PvScriptWebSimple
		{
			public $Titre = "D&eacute;connexion" ;
			public $TitreDocument = "D&eacute;connexion" ;
			public $UrlConnexionReussie = "" ;
			public $NomScriptConnexionReussie = "" ;
			public $MessageDeconnexionReussie = "Vous avez &eacute;t&eacute; d&eacute;connect&eacute; avec succ&egrave;s." ;
			public $MessageRetourAccueil = "Retour &agrave; la page d'accueil" ;
			public function PrepareRendu()
			{
				if(! $this->ZoneParent->EstNul($this->ZoneParent->Membership) && $this->ZoneParent->PossedeMembreConnecte())
				{
					$this->ZoneParent->Membership->LogoutMember($this->ZoneParent->Membership->MemberLogged->Id) ;
				}
				$url = '' ;
				if($this->NomScriptConnexionReussie != '' && isset($this->ZoneParent->Scripts[$this->NomScriptConnexionReussie]))
				{
					$url = $this->ZoneParent->Scripts[$this->NomScriptConnexionReussie] ;
				}
				elseif($this->UrlConnexionReussie != '')
				{
					$url = $this->UrlConnexionReussie ;
				}
				if($url != '')
				{
					redirect_to($url) ;
				}
			}
			protected function RenduDispositifBrut()
			{
				$ctn = '' ;
				if($this->MessageDeconnexionReussie != '')
				{
					$ctn .= '<p>'.$this->MessageDeconnexionReussie.'</p>' ;
				}
				$ctn .= '<p align="center"><a href="'.$this->ZoneParent->ObtientUrl().'">'.$this->MessageRetourAccueil.'</a></p>' ;
				return $ctn ;
			}
		}
		class PvScriptAjoutMembreMSWeb extends PvFormulaireAjoutDonneesSimple
		{
			public $Titre = "Ajouter un membre" ;
			public $TitreDocument = "Ajouter un membre" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireAjoutMembreMS" ;
		}
		class PvScriptInscriptionWeb extends PvFormulaireAjoutDonneesSimple
		{
			public $Titre = "Inscription" ;
			public $TitreDocument = "Inscription" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireAjoutMembreMS" ;
			public $Securiser = 0 ;
			public $FltCaptcha ;
			public $CompCaptcha ;
			public $NomParamCaptcha = "image_securise" ;
			public $IdProfilsAcceptes = array() ;
			public $LibelleCmdExecuter = "S'inscrire" ;
			public $IdProfilParDefaut = 1 ;
			public $InclureMsgConnexion = 1 ;
			public $AlignMsgConnexion = "center" ;
			public $FormatMsgConnexion = 'D&eacute;j&agrave; inscrit ? <a href="${url}">Connectez-vous !</a>' ;
			protected function ChargeConfigComposantFormulaireDonnees()
			{
				$form = & $this->ComposantFormulaireDonnees ;
				parent::ChargeConfigComposantFormulaireDonnees() ;
				if($this->Securiser)
				{
					$this->FltCaptcha = $form->InsereFltHttpPost($this->NomParamCaptcha) ;
					$this->CompCaptcha = $this->FltCaptcha->DeclareComposant("PvZoneCommonCaptcha") ;
				}
				$fltActiver = & $form->FiltreActiverMembre ;
				$fltActiver->ValeurParDefaut = 0 ;
				$fltActiver->Invisible = 1 ;
				$fltProfil = & $form->FiltreProfilMembre ;
				if(count($this->IdProfilsAcceptes) > 0)
				{
					$fourn = & $fltProfil->Composant->FournisseurDonnees ;
					$params = array_apply_prefix("idProfil", $this->IdProfilsAcceptes) ;
					$fourn->ParamsSelection = array() ;
					$fourn->RequeteSelection = "(select * from ".$fourn->BaseDonnees->EscapeTableName($this->ZoneParent->Membership->ProfileTable).' where '.$fourn->BaseDonnees->EscapeFieldName($this->ZoneParent->Membership->ProfileTable, $this->ZoneParent->Membership->IdProfileColumn)." in (" ;
					foreach($this->IdProfilsAcceptes as $i => $idProfil)
					{
						if($i > 0)
							$fourn->RequeteSelection .= ", " ;
						$fourn->ParamsSelection["idProfil".$i] = $idProfil ;
						$fourn->RequeteSelection .= $fourn->BaseDonnees->ParamPrefix."idProfil".$i ;
					}
					$fourn->RequeteSelection .= "))" ;
				}
				else
				{
					$fltProfil->ValeurParDefaut = $this->IdProfilParDefaut ;
					$fltProfil->Invisible = 1 ;
				}
				$form->CommandeExecuter->Libelle = $this->LibelleCmdExecuter ;
				$form->RedirigeAnnulerVersScript($this->ZoneParent->NomScriptConnexion) ;
			}
			public function RenduSpecifique()
			{
				$ctn = parent::RenduSpecifique() ;
				if($this->InclureMsgConnexion == 1)
				{
					$ctn .= $this->RenduMsgConnexion() ;
				}
				return $ctn ;
			}
			protected function RenduMsgConnexion()
			{
				$ctn = '' ;
				$params = array(
					'url' => $this->ZoneParent->ScriptConnexion->ObtientUrl(),
					'chemin_icone' => $this->ZoneParent->ScriptConnexion->CheminIcone,
					'titre' => $this->ZoneParent->ScriptConnexion->Titre
				) ;
				$ctn .= '<p align="'.$this->AlignMsgConnexion.'">'._parse_pattern($this->FormatMsgConnexion, $params).'</p>' ;
				return $ctn ;
			}
		}
		class PvScriptModifMembreMSWeb extends PvFormulaireModifDonneesSimple
		{
			public $Titre = "Modifier un membre" ;
			public $TitreDocument = "Modifier un membre" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireModifMembreMS" ;
		}
		class PvScriptSupprMembreMSWeb extends PvFormulaireSupprDonneesSimple
		{
			public $Titre = "Supprimer un membre" ;
			public $TitreDocument = "Supprimer un membre" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireSupprMembreMS" ;
		}
		class PvScriptModifPrefsWeb extends PvFormulaireModifDonneesSimple
		{
			public $Titre = "Modifier informations perso." ;
			public $TitreDocument = "Modifier informations perso." ;
			public $NomClasseFormulaireDonnees = "PvFormulaireModifMembreMS" ;
		}
		class PvScriptChangeMotPasseWeb extends PvFormulaireModifDonneesSimple
		{
			public $Titre = "Changer de mot de passe" ;
			public $TitreDocument = "Changer de mot de passe" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireChangeMotPasseMS" ;
		}
		class PvScriptDoitChangerMotPasseWeb extends PvFormulaireModifDonneesSimple
		{
			public $Titre = "Changer obligatoire de mot de passe" ;
			public $TitreDocument = "Changer obligatoire de mot de passe" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireDoitChangerMotPasseMS" ;
		}
		class PvScriptChangeMPMembreWeb extends PvFormulaireModifDonneesSimple
		{
			public $Titre = "Changer mot de passe membre" ;
			public $TitreDocument = "Changer mot de passe membre" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireChangeMPMembreMS" ;
		}
		class PvScriptListeMembresMSWeb extends PvScriptTableauDonneesSimple
		{
			public $Titre = "Liste des membres" ;
			public $TitreDocument = "Liste des membres" ;
			public $NomClasseTableauDonnees = "PvTableauMembresMSHtml" ;
		}
		class PvScriptAjoutProfilMSWeb extends PvFormulaireAjoutDonneesSimple
		{
			public $Titre = "Ajouter un profil" ;
			public $TitreDocument = "Ajouter un profil" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireAjoutProfilMS" ;
		}
		class PvScriptModifProfilMSWeb extends PvFormulaireModifDonneesSimple
		{
			public $Titre = "Modifier un profil" ;
			public $TitreDocument = "Modifier un profil" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireModifProfilMS" ;
		}
		class PvScriptSupprProfilMSWeb extends PvFormulaireSupprDonneesSimple
		{
			public $Titre = "Supprimer un profil" ;
			public $TitreDocument = "Supprimer un profil" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireSupprProfilMS" ;
		}
		class PvScriptListeProfilsMSWeb extends PvScriptTableauDonneesSimple
		{
			public $Titre = "Liste des profils" ;
			public $TitreDocument = "Liste des profils" ;
			public $NomClasseTableauDonnees = "PvTableauProfilsMSHtml" ;
		}
		class PvScriptAjoutRoleMSWeb extends PvFormulaireAjoutDonneesSimple
		{
			public $Titre = "Ajouter un r&ocirc;le" ;
			public $TitreDocument = "Ajouter un r&ocirc;le" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireAjoutRoleMS" ;
		}
		class PvScriptModifRoleMSWeb extends PvFormulaireModifDonneesSimple
		{
			public $Titre = "Modifier un r&ocirc;le" ;
			public $TitreDocument = "Modifier un r&ocirc;le" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireModifRoleMS" ;
		}
		class PvScriptSupprRoleMSWeb extends PvFormulaireSupprDonneesSimple
		{
			public $Titre = "Supprimer un r&ocirc;le" ;
			public $TitreDocument = "Supprimer un r&ocirc;le" ;
			public $NomClasseFormulaireDonnees = "PvFormulaireSupprRoleMS" ;
		}
		class PvScriptListeRolesMSWeb extends PvScriptTableauDonneesSimple
		{
			public $Titre = "Liste des r&ocirc;les" ;
			public $TitreDocument = "Liste des r&ocirc;les" ;
			public $NomClasseTableauDonnees = "PvTableauRolesMSHtml" ;
		}
		
		class PvFournisseurComposantIUMS
		{
			public static function RemplitFiltreEditionMembre(& $script)
			{
				$membership = $script->ZoneParent->Membership ;
				$i = 0 ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreLoginMembre") ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->LoginMemberColumn ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->LoginMemberColumn ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->LoginMemberLabel ;
				$i++ ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreMotPasseMembre") ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneMotPasseHtml") ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->PasswordMemberColumn ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->PasswordMemberColumn ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->PasswordMemberLabel ;
				if($membership->PasswordMemberExpr != "")
				{
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->ExpressionColonneLiee = $membership->PasswordMemberExpr.'('.$membership->Database->ExprParamPattern.')' ;
				}
				if($membership->FirstNameMemberColumn != "")
				{
					$i++ ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreNomMembre") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->FirstNameMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->FirstNameMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->FirstNameMemberLabel ;
				}
				if($membership->LastNameMemberColumn != "")
				{
					$i++ ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtrePrenomMembre") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->LastNameMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->LastNameMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->LastNameMemberLabel ;
				}
				if($membership->EmailMemberColumn != "")
				{
					$i++ ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreEmailMembre") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->EmailMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->EmailMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->EmailMemberLabel ;
				}
				if($membership->AddressMemberColumn != "")
				{
					$i++ ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreAdresseMembre") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->AddressMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->AddressMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->AddressMemberLabel ;
				}
				if($membership->ContactMemberColumn != "")
				{
					$i++ ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreContactMembre") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->ContactMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->ContactMemberLabel ;
				}
				if($membership->ADActivatedMemberColumn != "")
				{
					$i++ ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreActiverADMembre") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->ADActivatedMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->ADActivatedMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->ADActivatedMemberLabel ;
				}
				if($membership->EnableMemberColumn != "")
				{
					$i++ ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreActiverMembre") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneTexteHtml") ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->EnableMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->EnableMemberColumn ;
					$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->EnableMemberLabel ;
				}
				$script->ComposantFormulaireDonnees->FiltresEdition[$i] = $script->CreeFiltreHttpPost("filtreProfilMembre") ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomColonneLiee = $membership->ProfileMemberColumn ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->NomParametreDonnees = $membership->ProfileMemberColumn ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->DeclareComposant("PvZoneBoiteSelectHtml") ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Libelle = $membership->ProfileMemberLabel ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Composant->FournisseurDonnees = new PvFournisseurDonneesSql() ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Composant->FournisseurDonnees->BaseDonnees = & $membership->Database ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Composant->FournisseurDonnees->RequeteSelection = $membership->ProfileTable ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Composant->NomColonneValeur = $membership->ProfileMemberForeignKey ;
				$script->ComposantFormulaireDonnees->FiltresEdition[$i]->Composant->NomColonneLibelle = $membership->TitleProfileColumn ;
			}
			public static function InitFormulaireMembre(& $script)
			{
				$membership = $script->ZoneParent->Membership ;
				$script->ComposantFormulaireDonnees->FournisseurDonnees->BaseDonnees = $membership->Database ;
				$script->ComposantFormulaireDonnees->FournisseurDonnees->RequeteSelection = $membership->MemberTable ;
				$script->ComposantFormulaireDonnees->FournisseurDonnees->TableEdition = $membership->MemberTable ;
			}
			public static function RemplitFormulaireGlobalMembre(& $script)
			{
				PvFournisseurComposantIUMS::InitFormulaireMembre($script) ;
				$i = 0 ;
				$membership = $script->ZoneParent->Membership ;
				$script->ComposantFormulaireDonnees->FiltresLigneSelection[$i] = $script->CreeFiltreHttpGet("idMembre") ;
				$script->ComposantFormulaireDonnees->FiltresLigneSelection[$i]->ExpressionDonnees = $membership->Database->EscapeVariableName($membership->IdMemberColumn).' = <self>' ;
				PvFournisseurComposantIUMS::RemplitFiltreEditionMembre($script) ;
			}
			public static function RemplitFormulairePrefsMembre(& $script)
			{
				$membership = $script->ZoneParent->Membership ;
				PvFournisseurComposantIUMS::InitFormulaireMembre($script) ;
				$i = 0 ;
				$script->ComposantFormulaireDonnees->FiltresLigneSelection[$i] = $script->CreeFiltreMembreConnecte($membership->IdMemberColumn) ;
				$script->ComposantFormulaireDonnees->FiltresLigneSelection[$i]->ExpressionDonnees = $membership->Database->EscapeFieldName($membership->MemberTable, $membership->IdMemberColumn).' = <self>' ;
				PvFournisseurComposantIUMS::RemplitFiltreEditionMembre($script) ;
			}
		}
	}
	
?>