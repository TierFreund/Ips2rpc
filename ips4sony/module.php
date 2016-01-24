<?
class ips4sony extends RpcModule {
	
	public function Create(){
        parent::Create();
		$this->SetPropertyString('ConnectionType','soap');
	}
    public function ApplyChanges(){
        parent::ApplyChanges();
		$this->RegisterAction('Volume','Lautstärke',$typ=1,$profil='');
		$this->RegisterAction('Mute','Stumm',$typ=0,$profil='');
		$this->CheckConfig();
	}	
	
	protected function CheckConfig(){
		if(!parent::CheckConfig())return null;
		return true;
	}
	
	public function RequestAction($Ident, $Value){
		switch($Ident) {
			case 'Mute'    : $this->SetMute($Value); break;
			case 'Volume'  : $this->SetVolume($Value); break;
			default        : throw new Exception("Invalid Ident: $Ident");
		}
	}
	protected function CreateApi($url, $port, $type){
		require_once('/../rpc2sony.class.php');
		return new Rpc2Sony($url, $port, $type);	
	}
	
	
}	
?>