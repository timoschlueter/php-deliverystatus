<?php
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	
	class Deliverystatus {
		private $loginUsername;
		private $loginPassword;
		private $loginUrl;
		private $logoutUrl;
		private $ch;
		private $cookieJar;
		private $loggedIn;
		private $doc;
		private $timeout;
		private $deliveries;
		
		public function __construct() {
			libxml_use_internal_errors(true);
			$this->loginUrl = "https://junecloud.com/sync/deliveries/";
			$this->logoutUrl = "https://junecloud.com/sync/?cmd=logout";
			$this->ch = curl_init();
			$this->doc = new DOMDocument();
			$this->xpath = "";
			$this->cookieJar = getcwd() . "/cookiejar";
			$this->loggedIn = false;
			$this->timeout = 10;
			$this->deliveryList = array();
		}
		
		public function login() {
			$postData = array(
				"cmd" => "login",
				"type" => "web",
				"email" => $this->loginUsername,
				"password" => $this->loginPassword,
				"newpassword" => "",
				"confirmpass" => "",
				"name" => ""
			);

			curl_setopt($this->ch, CURLOPT_URL, $this->loginUrl); 
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($this->ch,CURLOPT_POST, count($postData));
			curl_setopt($this->ch,CURLOPT_POSTFIELDS, $postData);
			curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookieJar);
			curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookieJar);

			$responseOutput = curl_exec($this->ch); 
			$responseStatus = curl_getinfo($this->ch);
									
			if ($responseStatus["http_code"] == 200) {
				$this->doc->loadHTML($responseOutput);
				$deliveryBlock = $this->doc->getElementById('deliveries');
				
				if ($deliveryBlock) {
					$this->loggedIn = true;
					$this->xpath = new DOMXpath($this->doc);
				}
			}
		}
		
		public function logout() {
			curl_setopt($this->ch, CURLOPT_URL, $this->logoutUrl);
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookieJar);
			curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookieJar);
			curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
			if (curl_exec($this->ch)) {
				curl_close($this->ch); 
				$this->loggedIn = false;
				return true;
			} else {
				return false;
			}
		}
		
		public function setUsername($loginUsername) {
			$this->loginUsername = $loginUsername;
		}
		
		public function setPassword($loginPassword) {
			$this->loginPassword = $loginPassword;
		}
		
		public function setTimeout($timeout) {
			$this->timeout = $timeout;
		}
		
		public function getUsername() {
			return $this->loginUsername;
		}
		
		public function gettTimeout() {
			return $this->timeout;
		}
				
		public function getDeliveries() {
			if ($this->loggedIn) {	
						
				$deliveries = $this->xpath->query('//*[@class="delivery"]');
				
				foreach ($deliveries as $delivery) {
					$deliveryEntry = new StdClass();
					
					/* Extract the title */
					$titles = $this->xpath->query('descendant::h4', $delivery);
									
					foreach($titles as $title) {
						/* Save the title */
						$deliveryTitle = $title->nodeValue;
						
						/* Extract the tracking number */
						$trackingnumbers = $this->xpath->query('descendant::*[@class="small"]', $title);
						
						foreach($trackingnumbers as $trackingnumber) {
							/* Save the tracking number */
							$deliveryTrackingNumber = $trackingnumber->nodeValue;
							
							/* Strip the tracking number from title */
							$deliveryTitle = str_replace($deliveryTrackingNumber, "", $deliveryTitle);
							
							/* Strip the brackets from tracking number */
							$deliveryTrackingNumber = str_replace("(", "", $deliveryTrackingNumber);
							$deliveryTrackingNumber = str_replace(")", "", $deliveryTrackingNumber);
						}
						
						/* Extract the tracking url */
						$trackingUrls = $this->xpath->query('descendant::a', $title);
						
						foreach($trackingUrls as $trackingUrl) {
							/* Save the tracking url */
							$deliveryUrl = $trackingUrl->getAttribute('href');
						}
					}
					
					/* Extract the information */
					$infos = $this->xpath->query('descendant::*[@class="info"]', $delivery);

					foreach($infos as $info) {
						$deliveryInfo = $info->nodeValue;
					}
					
					/* Put it all together ... */
					$deliveryEntry->title = $deliveryTitle;
					$deliveryEntry->tracking_url = $deliveryUrl;
					$deliveryEntry->tracking_number = $deliveryTrackingNumber;
					$deliveryEntry->information = $deliveryInfo;
								
					/* ... and push it into the array */
					array_push($this->deliveryList, $deliveryEntry);
				}
				return $this->deliveryList;
			} else {
				/* Not logged in */
			}					
		}
	}
?>