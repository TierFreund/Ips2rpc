<?
require_once('/../RpcModule.class.php');
require_once('/../RpcIoSoap.class.php');
class ips4samsung extends RpcModule {
	
	public function Create(){
        parent::Create();
		$this->SetProperty('ConnectionType','soap');
	}
    public function ApplyChanges(){
        parent::ApplyChanges();
		$this->RegisterAction('Volume','Lautstärke',$typ=1,$profil='');
		$this->RegisterAction('Mute','Stumm',$typ=0,$profil='RPC.OnOff');
		if ($this->CheckConfig()===true){
			$this->Update();
		}
	}	
	
	protected function CheckConfig(){
		if(!parent::CheckConfig())return null;
		$this->SetStatus(STATUS_OK);
		return true;
	}
	
	protected function CreateApi($url, $port, $type){
		require_once('/../rpc2samsung.class.php');
		return new Rpc2Samsung($url, $port, $type);	
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
	
	
}	
?>