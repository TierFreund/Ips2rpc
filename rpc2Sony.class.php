<?
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 |  Class           :rpc2Sony extends uRpcDevice                                  |
 |  Version         :2.2                                                          |
 |  BuildDate       :Tue 19.01.2016 11:21:28                                      |
 |  Publisher       :(c)2016 Xaver Bauer                                          |
 |  Contact         :xaver65@gmail.com                                            |
 |  Desc            :PHP Classes to Control MULTI CHANNEL AV RECEIVER             |
 |  port            :8080                                                         |
 |  base            :http://192.168.112.61:8080                                   |
 |  scpdurl         :/description.xml                                             |
 |  modelName       :STR-DN1050                                                   |
 |  deviceType      :urn:schemas-upnp-org:device:MediaRenderer:1                  |
 |  friendlyName    :sony                                                         |
 |  manufacturer    :Sony Corporation                                             |
 |  manufacturerURL :http://www.sony.net/                                         |
 |  modelNumber     :JB3.2                                                        |
 |  modelURL        :                                                             |
 |  UDN             :uuid:5f9ec1b3-ed59-1900-4530-d8d43cd2af47                    |
 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!DEFINED('RPC2SONY_STATE_STOP')) {
  DEFINE('RPC2SONY_STATE_STOP',0);
  DEFINE('RPC2SONY_STATE_PREV',1);
  DEFINE('RPC2SONY_STATE_PLAY',2);
  DEFINE('RPC2SONY_STATE_PAUSE',3);
  DEFINE('RPC2SONY_STATE_NEXT',4);
  DEFINE('RPC2SONY_STATE_TRANS',5);
  DEFINE('RPC2SONY_STATE_ERROR',6);
}
class rpc2Sony extends uRpcDevice {
  protected $_boRepeat=false;
  protected $_boShuffle=false;
  protected $_boAll=false;
  // Name:string
  protected function GetServiceConnData($name){
    switch($name){
      case  'RenderingControl' : return [8080,"urn:schemas-upnp-org:service:RenderingControl:1","/RenderingControl/ctrl","/RenderingControl/evt","/RenderingControl/desc.xml"];
      case 'ConnectionManager' : return [8080,"urn:schemas-upnp-org:service:ConnectionManager:1","/ConnectionManager/ctrl","/ConnectionManager/evt","/ConnectionManager/desc.xml"];
      case       'AVTransport' : return [8080,"urn:schemas-upnp-org:service:AVTransport:1","/AVTransport/ctrl","/AVTransport/evt","/AVTransport/desc.xml"];
      case              'IRCC' : return [8080,"urn:schemas-sony-com:service:IRCC:1","/upnp/control/IRCC","","/IRCCSCPD.xml"];
      case          'X_Tandem' : return [8080,"urn:schemas-sony-com:service:X_Tandem:1","/upnp/control/TANDEM","","/TANDEMSCPD.xml"];
    }
    return null;
  }
  // url:string, defaultPort:ui4, requestType:string
  public function __construct($url, $defaultPort=8080, $requestType='soap'){
    parent::__construct($url,$defaultPort,$requestType);
  }

  public function GetCurrentConnectionIDs(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('ConnectionIDs');
    return self::Call('ConnectionManager','GetCurrentConnectionIDs',null,$filter);
  }
  // ConnectionID:i4
  public function GetCurrentConnectionInfo($ConnectionID){
    if (!$this->GetOnlineState()) return null;
    $args=array('ConnectionID'=>$ConnectionID);
    $filter=array('RcsID','AVTransportID','ProtocolInfo','PeerConnectionManager','PeerConnectionID','Direction','Status');
    return self::Call('ConnectionManager','GetCurrentConnectionInfo',$args,$filter);
  }
  // Instance:ui4
  public function GetCurrentTransportActions($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('Actions');
    return self::Call('AVTransport','GetCurrentTransportActions',$args,$filter);
  }
  // Instance:ui4
  public function GetDeviceCapabilities($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('PlayMedia','RecMedia','RecQualityModes');
    return self::Call('AVTransport','GetDeviceCapabilities',$args,$filter);
  }
  // Instance:ui4
  public function GetMediaInfo($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('NrTracks','MediaDuration','CurrentURI','CurrentURIMetaData','NextURI','NextURIMetaData','PlayMedium','RecordMedium','WriteStatus');
    return self::Call('AVTransport','GetMediaInfo',$args,$filter);
  }
  // Instance:ui4, Channel:string
  public function GetMute($Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel);
    $filter=array('CurrentMute');
    return self::Call('RenderingControl','GetMute',$args,$filter);
  }
  // Instance:ui4
  public function GetPositionInfo($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('Track','TrackDuration','TrackMetaData','TrackURI','RelTime','AbsTime','RelCount','AbsCount');
    return self::Call('AVTransport','GetPositionInfo',$args,$filter);
  }

  public function GetProtocolInfo(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Source','Sink');
    return self::Call('ConnectionManager','GetProtocolInfo',null,$filter);
  }
  // Instance:ui4
  public function GetRepeat($Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    return $this->_boRepeat;
  }
  // Instance:ui4
  public function GetShuffle($Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    return $this->_boShuffle;
  }
  // Instance:ui4
  public function GetState($Instance=0) {
    $states=array('STOPPED'=>RPC2SONY_STATE_STOP,'PLAYING'=>RPC2SONY_STATE_PLAY,'PAUSED_PLAYBACK'=>RPC2SONY_STATE_PAUSE,'TRANSITIONING'=>RPC2SONY_STATE_TRANS,'NO_MEDIA_PRESENT'=>RPC2SONY_STATE_ERROR);
    $v=self::GetTransportInfo($Instance);
    return ($v&&($s=$v['CurrentTransportState'])&&isset($a[$s]))?$a[$s]:RPC2SONY_STATE_ERROR;
  }
  // Instance:ui4
  public function GetTransportInfo($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentTransportState','CurrentTransportStatus','CurrentSpeed');
    return self::Call('AVTransport','GetTransportInfo',$args,$filter);
  }
  // Instance:ui4
  public function GetTransportSettings($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('PlayMode','RecQualityMode');
    return self::Call('AVTransport','GetTransportSettings',$args,$filter);
  }
  // Instance:ui4, Channel:string
  public function GetVolume($Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel);
    $filter=array('CurrentVolume');
    return self::Call('RenderingControl','GetVolume',$args,$filter);
  }
  // Instance:ui4
  public function ListPresets($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentPresetNameList');
    return self::Call('RenderingControl','ListPresets',$args,$filter);
  }
  // Instance:ui4
  public function Next($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Next',$args,null);
  }
  // Instance:ui4
  public function Pause($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Pause',$args,null);
  }
  // Instance:ui4, Speed:ui4
  public function Play($Instance=0,$Speed=1){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Speed'=>$Speed);
    return self::Call('AVTransport','Play',$args,null);
  }
  // Instance:ui4
  public function Previous($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Previous',$args,null);
  }
  // Target:string, Instance:ui4, Unit:string
  public function Seek($Target,$Instance=0,$Unit='TRACK_NR'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Unit'=>$Unit,'Target'=>$Target);
    return self::Call('AVTransport','Seek',$args,null);
  }
  // Instance:ui4, PresetName:string
  public function SelectPreset($Instance=0,$PresetName='FactoryDefaults'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'PresetName'=>$PresetName);
    return self::Call('RenderingControl','SelectPreset',$args,null);
  }
  // CurrentURI:string, CurrentURIMetaData:string, Instance:ui4
  public function SetAVTransportURI($CurrentURI,$CurrentURIMetaData,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CurrentURI'=>$CurrentURI,'CurrentURIMetaData'=>$CurrentURIMetaData);
    return self::Call('AVTransport','SetAVTransportURI',$args,null);
  }
  // DesiredMute:boolean, Instance:ui4, Channel:string
  public function SetMute($DesiredMute,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'DesiredMute'=>$DesiredMute);
    return self::Call('RenderingControl','SetMute',$args,null);
  }
  // NextURI:string, NextURIMetaData:string, Instance:ui4
  public function SetNextAVTransportURI($NextURI,$NextURIMetaData,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'NextURI'=>$NextURI,'NextURIMetaData'=>$NextURIMetaData);
    return self::Call('AVTransport','SetNextAVTransportURI',$args,null);
  }
  // NewPlayMode:string, Instance:ui4
  public function SetPlayMode($NewPlayMode,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'NewPlayMode'=>$NewPlayMode);
    return self::Call('AVTransport','SetPlayMode',$args,null);
  }
  // Repeat:boolean, Instance:ui4
  public function SetRepeat($Repeat,$Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($InstanceID);
    $this->SetPlayModes($this->_PlayModes[$this->_boRepeat=$boRepeat][$this->_boShuffle][$this->_boAll]);
  }
  // Shuffle:boolean, Instance:ui4
  public function SetShuffle($Shuffle,$Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    $this->SetPlayMode($this->_PlayModes[$this->_boRepeat][$this->_boShuffle=$boShuffle][$this->_boAll]);
  }
  // NewState:ui2, Instance:ui4
  public function SetState($NewState,$Instance=0) {
    switch($NewState){
      case RPC2SONY_STATE_STOP : $s=$this->Stop($Instance)?RPC2SONY_STATE_STOP:RPC2SONY_STATE_ERROR; break;
      case RPC2SONY_STATE_PREV : $s=$this->Previous($Instance)?RPC2SONY_STATE_PLAY:RPC2SONY_STATE_STOP;break;
      case RPC2SONY_STATE_PLAY : $s=$this->Play($Instance)?RPC2SONY_STATE_PLAY:RPC2SONY_STATE_STOP; break;
      case RPC2SONY_STATE_PAUSE: $s=$this->Pause($Instance)?RPC2SONY_STATE_PAUSE:RPC2SONY_STATE_STOP;break;
      case RPC2SONY_STATE_NEXT : $s=$this->Next($Instance)?RPC2SONY_STATE_PLAY:RPC2SONY_STATE_STOP; break;
      default : return RPC2SONY_STATE_ERROR;
    }
    return $s;
  }
  // DesiredVolume:ui2, Instance:ui4, Channel:string
  public function SetVolume($DesiredVolume,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'DesiredVolume'=>$DesiredVolume);
    return self::Call('RenderingControl','SetVolume',$args,null);
  }
  // Instance:ui4
  public function Stop($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Stop',$args,null);
  }
  // Instance:ui4
  protected function UpdatePlayMode($Instance=0){
    static $modes=array(
        'NORMAL'=>array(false,false,false),
        'RANDOM'=>array(false,false,false),
        'REPEAT_ONE'=>array(true,false,false),
        'REPEAT_ALL'=>array(true,false,true),
    );
    if(empty($this->_PlayModes))
      foreach($modes as $k=>$a)$this->_PlayModes[$a[0]][$a[1]][$a[2]]=$k;
    if(!$t=$this->GetTransportSettings($Instance))return false;
    list($this->_boRepeat,$this->_boShuffle,$this->_boAll)=$modes[$t['PlayMode']];
  }
  // AVTInstanceID:ui4, ActionDirective:string
  public function X_ExecuteOperation($AVTInstanceID,$ActionDirective){
    if (!$this->GetOnlineState()) return null;
    $args=array('AVTInstanceID'=>$AVTInstanceID,'ActionDirective'=>$ActionDirective);
    $filter=array('Result');
    return self::Call('AVTransport','X_ExecuteOperation',$args,$filter);
  }
  // AVTInstanceID:ui4
  public function X_GetOperationList($AVTInstanceID){
    if (!$this->GetOnlineState()) return null;
    $args=array('AVTInstanceID'=>$AVTInstanceID);
    $filter=array('OperationList');
    return self::Call('AVTransport','X_GetOperationList',$args,$filter);
  }
  // CategoryCode:string
  public function X_GetStatus($CategoryCode){
    if (!$this->GetOnlineState()) return null;
    $args=array('CategoryCode'=>$CategoryCode);
    $filter=array('CurrentStatus','CurrentCommandInfo');
    return self::Call('IRCC','X_GetStatus',$args,$filter);
  }
  // IRCCCode:string
  public function X_SendIRCC($IRCCCode){
    if (!$this->GetOnlineState()) return null;
    $args=array('IRCCCode'=>$IRCCCode);
    return self::Call('IRCC','X_SendIRCC',$args,null);
  }

  public function X_Tandem(){
    if (!$this->GetOnlineState()) return null;
    return self::Call('X_Tandem','X_Tandem',null,null);
  }
}
?>