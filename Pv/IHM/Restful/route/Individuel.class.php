<?php
	
	if(! defined('PV_ROUTE_INDIVIDUEL_RESTFUL'))
	{
		define('PV_ROUTE_INDIVIDUEL_RESTFUL', 1) ;
		
		class PvRouteElementRestful extends PvRouteEditableRestful
		{
			public $MethodeHttp = "" ;
			public $AutoriserSelect = 1 ;
			public $AutoriserAjout = 1 ;
			public $AutoriserModif = 1 ;
			public $AutoriserSuppr = 1 ;
			protected $ModeEdition = 0 ;
			public function ApprouveAppel()
			{
				$methodeHttp = $this->ApiParent->MethodeHttp ;
				$ok = false ;
				if($methodeHttp == "GET" && $this->AutoriserSelect == 1)
				{
					$ok = true ;
					$this->ModeEdition = 0 ;
				}
				elseif($methodeHttp == "PUT" && $this->AutoriserAjout == 1)
				{
					$ok = true ;
					$this->ModeEdition = 1 ;
				}
				elseif(($methodeHttp == "POST" || $methodeHttp == "PATCH") && $this->AutoriserModif == 1)
				{
					$ok = true ;
					$this->ModeEdition = 2 ;
				}
				elseif($methodeHttp == "DELETE" && $this->AutoriserModif == 1)
				{
					$ok = true ;
					$this->ModeEdition = 3 ;
				}
				return $ok ;
			}
			public function SelectionEnCours()
			{
				return $this->ModeEdition == 0 ;
			}
			public function CreationEnCours()
			{
				return $this->ModeEdition == 1 ;
			}
			public function AjoutEnCours()
			{
				return $this->ModeEdition == 1 ;
			}
			public function ModifEnCours()
			{
				return $this->ModeEdition == 2 ;
			}
			public function SupprEnCours()
			{
				return $this->ModeEdition == 3 ;
			}
			protected function AppliqueEdition()
			{
				if(count($this->FiltresSelection) == 0)
				{
					$this->RenseigneException("Au moins un filtre de selection doit etre defini") ;
					return false ;
				}
				if($this->ModeEdition == 0)
				{
					$this->ConfirmeSucces() ;
					return true ;
				}
				if(count($this->FiltresEdition) == 0)
				{
					$this->RenseigneErreur("Au moins un filtre d'edition doit etre defini") ;
					return ;
				}
				switch($this->ModeEdition)
				{
					case 1 :
					{
						$succes = $this->FournisseurDonnees->AjoutElement($this->FiltresEdition) ;
					}
					break ;
					case 2 :
					{
						$succes = $this->FournisseurDonnees->ModifElement($this->FiltresSelection, $this->FiltresEdition) ;
					}
					break ;
					case 3 :
					{
						$succes = $this->FournisseurDonnees->SupprElement($this->FiltresSelection) ;
					}
					break ;
					default :
					{
						$this->RenseigneErreur("Le mode d'&eacute;dition de la commande est inconnue") ;
					}
					break ;
				}
				if($this->FournisseurDonnees->BaseDonnees->ConnectionException != '')
				{
					$this->RenseigneErreur("Erreur SQL : ".$this->FournisseurDonnees->BaseDonnees->ConnectionException) ;
				}
				else
				{
					$this->ConfirmeSucces() ;
				}
				return $this->SuccesReponse() ;
			}
			protected function ExtraitColonnesDonnees(& $filtres)
			{
				$cols = array() ;
				foreach($filtres as $i => & $filtre)
				{
					if($filtre->NePasLireColonne == 1)
					{
						continue ;
					}
					$cols[$i] = new PvDefColRestful() ;
					$cols[$i]->NomDonnees = $filtre->NomColonneLiee ;
					$cols[$i]->AliasDonnees = $filtre->AliasParametreDonnees ;
				}
				// print_r($cols) ;
				return $cols ;
			}
			protected function CalculeElementsRendu()
			{
				$this->ElementsEnCours = array() ;
				$this->ElementEnCours = array() ;
				$this->ElementEnCoursTrouve = 0 ;
				if($this->ModeEdition == 1)
				{
					$this->ElementEnCoursTrouve = 1 ;
					$this->ContenuReponse->data = array() ;
					$this->ConfirmeSucces() ;
					return ;
				}
				if($this->ModeEdition < 3)
				{
					$this->ElementsEnCours = $this->FournisseurDonnees->SelectElements(
						$this->ExtraitColonnesDonnees($this->FiltresEdition), 
						$this->FiltresSelection
					) ;
					if($this->FournisseurDonnees->ExceptionTrouvee())
					{
						$this->MessageException = $this->FournisseurDonnees->MessageException() ;
					}
					if($this->MessageException == '')
					{
						if(count($this->ElementsEnCours) > 0)
						{
							$this->ElementEnCours = $this->ElementsEnCours[0] ;
							$lgn = array() ;
							$nomFiltres = array_keys($this->FiltresEdition) ;
							foreach($nomFiltres as $i => $nomFiltre)
							{
								$filtre = & $this->FiltresEdition[$nomFiltre] ;
								if($filtre->NomParametreDonnees == '')
								{
									continue ;
								}
								if(isset($this->ElementEnCours[$filtre->NomParametreDonnees]))
								{
									$lgn[$filtre->NomParametreDonnees] = $this->ElementEnCours[$filtre->NomParametreDonnees] ;
								}
								else
								{
									$lgn[$filtre->NomParametreDonnees] = null ;
								}
							}
							$this->ContenuReponse->data = $lgn ;
							$this->ElementEnCoursTrouve = 1 ;
						}
						$this->ConfirmeSucces() ;
					}
					else
					{
						$this->RenseigneException($this->MessageException) ;
					}
				}
				else
				{
					$this->ElementEnCoursTrouve = 1 ;
				}
			}
			protected function TermineExecution()
			{
				if($this->AppliqueEdition())
				{
					$this->CalculeElementsRendu() ;
				}
			}
		}
		
		class PvRouteIndividuelRestful extends PvRouteElementRestful
		{
		}
		class PvRouteSingleRestful extends PvRouteElementRestful
		{
		}
	
	}
	
?>