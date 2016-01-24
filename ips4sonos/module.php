<?
require_once('/../RpcModule.class.php');
require_once('/../RpcIoSoap.class.php');
class ips4sonos extends RpcModule {
	
	public function Create(){
        parent::Create();
		$this->SetProperty('ConnectionType','soap');
	}
    public function ApplyChanges(){
        parent::ApplyChanges();
		
		$this->RegisterAction('Volume','Lautstärke',$typ=1,$profil='');
		$this->RegisterAction('Mute','Stumm',$typ=0,$profil='RPC.OnOff');

		$this->RegisterVariableInteger('GroupMember','Gruppe',0);
		if ($this->CheckConfig()===true){
			$zoneConfig=IPS_GetKernelDir().'/modules/ips2rpc/sonos_zone.config';
	
			if(!file_exists($zoneConfig)){
				$file=$this->API()->BaseUrl(true).'/status/topology';
					if($xml=simplexml_load_file( $file )){
					$out=[];
					foreach($xml->ZonePlayers->ZonePlayer as $item){
						if($v=((array)$item->attributes()))$v=array_shift($v);
						$v['name']=(string)$item;
						$out[$v['name']]=$v;
					}
					file_put_contents($zoneConfig,serialize($out));
				}
			}				

			$this->Update();
			
		}
	}	
	
	protected function CheckConfig(){
		if(!parent::CheckConfig())return null;
		$this->SetStatus(STATUS_OK);
		return true;
	}
	
	protected function CreateApi($url, $port, $type){
		require_once('/../rpc2sonos.class.php');
		return new Rpc2Sonos($url, $port, $type);	
	}
	public function Test(){
		if(!parent::Test())return false;
			
		return true;
	}
	
	public function SetMute(boolean $NewValue, integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		return $this->API()->SetMute($NewValue, $Instance);
	}	
	public function GetMute(integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		return $this->API()->GetMute($Instance);
	}	
	public function SetVolume(integer $NewValue, integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		return $this->API()->SetMute($NewValue, $Instance);
	}	
	public function GetVolume(integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		return $this->API()->GetVolume($Instance);
	}	
	
	public function Play(integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		$r=$this->API()->Play($Instance);
	}
	public function Stop(integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		$r=$this->API()->Stop($Instance);
	}
	public function Pause(integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		$r=$this->API()->Pause($Instance);
	}
	public function Next(integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		$r=$this->API()->Next($Instance);
	}
	public function Prev(integer $Instance=null){
		if(!isSet($Instance))$Instance=0;
		$r=$this->API()->Previous($Instance);
	}
		
	
}	
?>