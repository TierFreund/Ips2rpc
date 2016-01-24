<?
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 |  Class           :rpc2Samsung extends uRpcDevice                               |
 |  Version         :2.2                                                          |
 |  BuildDate       :Tue 19.01.2016 11:21:28                                      |
 |  Publisher       :(c)2016 Xaver Bauer                                          |
 |  Contact         :xaver65@gmail.com                                            |
 |  Desc            :PHP Classes to Control Samsung TV DMR                        |
 |  port            :7676                                                         |
 |  base            :http://192.168.112.60:7676                                   |
 |  scpdurl         :/smp_16_                                                     |
 |  modelName       :UE55F6400                                                    |
 |  deviceType      :urn:schemas-upnp-org:device:MediaRenderer:1                  |
 |  friendlyName    :[TV] Samsung                                                 |
 |  manufacturer    :Samsung Electronics                                          |
 |  manufacturerURL :http://www.samsung.com/sec                                   |
 |  modelNumber     :AllShare1.0                                                  |
 |  modelURL        :http://www.samsung.com/sec                                   |
 |  UDN             :uuid:0ee6b280-00fa-1000-b849-0c891041f72d                    |
 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!DEFINED('RPC2SAMSUNG_STATE_STOP')) {
  DEFINE('RPC2SAMSUNG_STATE_STOP',0);
  DEFINE('RPC2SAMSUNG_STATE_PREV',1);
  DEFINE('RPC2SAMSUNG_STATE_PLAY',2);
  DEFINE('RPC2SAMSUNG_STATE_PAUSE',3);
  DEFINE('RPC2SAMSUNG_STATE_NEXT',4);
  DEFINE('RPC2SAMSUNG_STATE_TRANS',5);
  DEFINE('RPC2SAMSUNG_STATE_ERROR',6);
}
class rpc2samsung extends RpcDevice {
  protected $_boRepeat=false;
  protected $_boShuffle=false;
  protected $_boAll=false;
  // Name:string
  protected function GetServiceConnData($name){
    switch($name){
      case   'RenderingControl' : return [7676,"urn:schemas-upnp-org:service:RenderingControl:1","/smp_18_","/smp_19_","/smp_17_"];
      case  'ConnectionManager' : return [7676,"urn:schemas-upnp-org:service:ConnectionManager:1","/smp_21_","/smp_22_","/smp_20_"];
      case        'AVTransport' : return [7676,"urn:schemas-upnp-org:service:AVTransport:1","/smp_24_","/smp_25_","/smp_23_"];
      case               'dial' : return [7676,"urn:dial-multiscreen-org:service:dial:1","/smp_28_","/smp_29_","/smp_27_"];
      case 'MultiScreenService' : return [7676,"urn:samsung.com:service:MultiScreenService:1","/smp_8_","/smp_9_","/smp_7_"];
      case       'MainTVAgent2' : return [7676,"urn:samsung.com:service:MainTVAgent2:1","/smp_4_","/smp_5_","/smp_3_"];
    }
    return null;
  }
  // url:string, defaultPort:ui4, requestType:string
  public function __construct($url, $defaultPort=7676, $requestType='soap'){
    parent::__construct($url,$defaultPort?$defaultPort:7676,$requestType);
  }
  // ReservationType:string, RemindInfo:string
  public function AddSchedule($ReservationType,$RemindInfo){
    if (!$this->GetOnlineState()) return null;
    $args=array('ReservationType'=>$ReservationType,'RemindInfo'=>$RemindInfo);
    $filter=array('Result','ConflictRemindInfo','ConflictRemindInfoURL');
    return self::Call('MainTVAgent2','AddSchedule',$args,$filter);
  }
  // ReservationType:string, RemindInfo:string
  public function ChangeSchedule($ReservationType,$RemindInfo){
    if (!$this->GetOnlineState()) return null;
    $args=array('ReservationType'=>$ReservationType,'RemindInfo'=>$RemindInfo);
    $filter=array('Result','ConflictRemindInfo','ConflictRemindInfoURL');
    return self::Call('MainTVAgent2','ChangeSchedule',$args,$filter);
  }
  // PIN:string
  public function CheckPIN($PIN){
    if (!$this->GetOnlineState()) return null;
    $args=array('PIN'=>$PIN);
    $filter=array('Result');
    return self::Call('MainTVAgent2','CheckPIN',$args,$filter);
  }
  // ConnectionID:i4
  public function ConnectionComplete($ConnectionID){
    if (!$this->GetOnlineState()) return null;
    $args=array('ConnectionID'=>$ConnectionID);
    return self::Call('ConnectionManager','ConnectionComplete',$args,null);
  }
  // AntennaMode:ui4, ChannelList:string
  public function DeleteChannelList($AntennaMode,$ChannelList){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'ChannelList'=>$ChannelList);
    $filter=array('Result');
    return self::Call('MainTVAgent2','DeleteChannelList',$args,$filter);
  }
  // AntennaMode:ui4, ChannelList:string, PIN:string
  public function DeleteChannelListPIN($AntennaMode,$ChannelList,$PIN){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'ChannelList'=>$ChannelList,'PIN'=>$PIN);
    $filter=array('Result');
    return self::Call('MainTVAgent2','DeleteChannelListPIN',$args,$filter);
  }
  // UID:string
  public function DeleteRecordedItem($UID){
    if (!$this->GetOnlineState()) return null;
    $args=array('UID'=>$UID);
    $filter=array('Result');
    return self::Call('MainTVAgent2','DeleteRecordedItem',$args,$filter);
  }
  // UID:string
  public function DeleteSchedule($UID){
    if (!$this->GetOnlineState()) return null;
    $args=array('UID'=>$UID);
    $filter=array('Result');
    return self::Call('MainTVAgent2','DeleteSchedule',$args,$filter);
  }

  public function DestoryGroupOwner(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result');
    return self::Call('MainTVAgent2','DestoryGroupOwner',null,$filter);
  }
  // AntennaMode:ui4, Source:string, Destination:string, ForcedFlag:string
  public function EditChannelNumber($AntennaMode,$Source,$Destination,$ForcedFlag){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'Source'=>$Source,'Destination'=>$Destination,'ForcedFlag'=>$ForcedFlag);
    $filter=array('Result');
    return self::Call('MainTVAgent2','EditChannelNumber',$args,$filter);
  }
  // SourceType:string, SourceNameType:string
  public function EditSourceName($SourceType,$SourceNameType){
    if (!$this->GetOnlineState()) return null;
    $args=array('SourceType'=>$SourceType,'SourceNameType'=>$SourceNameType);
    $filter=array('Result');
    return self::Call('MainTVAgent2','EditSourceName',$args,$filter);
  }

  public function EnforceAKE(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result');
    return self::Call('MainTVAgent2','EnforceAKE',null,$filter);
  }

  public function GetACRCurrentChannelName(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','ChannelName');
    return self::Call('MainTVAgent2','GetACRCurrentChannelName',null,$filter);
  }

  public function GetACRCurrentProgramName(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','ProgramName');
    return self::Call('MainTVAgent2','GetACRCurrentProgramName',null,$filter);
  }

  public function GetACRMessage(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','Message');
    return self::Call('MainTVAgent2','GetACRMessage',null,$filter);
  }

  public function GetAPInformation(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','APInformation');
    return self::Call('MainTVAgent2','GetAPInformation',null,$filter);
  }

  public function GetAVOffStatus(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','AVOffStatus');
    return self::Call('MainTVAgent2','GetAVOffStatus',null,$filter);
  }
  // AntennaMode:ui4, Channel:string
  public function GetAllProgramInformationURL($AntennaMode,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'Channel'=>$Channel);
    $filter=array('Result','AllProgramInformationURL');
    return self::Call('MainTVAgent2','GetAllProgramInformationURL',$args,$filter);
  }

  public function GetAvailableActions(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','AvailableActions');
    return self::Call('MainTVAgent2','GetAvailableActions',null,$filter);
  }

  public function GetBannerInformation(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','BannerInformation');
    return self::Call('MainTVAgent2','GetBannerInformation',null,$filter);
  }
  // Instance:ui4
  public function GetBrightness($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentBrightness');
    return self::Call('RenderingControl','GetBrightness',$args,$filter);
  }

  public function GetChannelListURL(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','ChannelListVersion','SupportChannelList','ChannelListURL','ChannelListType','SatelliteID');
    return self::Call('MainTVAgent2','GetChannelListURL',null,$filter);
  }
  // AntennaMode:ui4, Channel:string
  public function GetChannelLockInformation($AntennaMode,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('Channel'=>$Channel,'AntennaMode'=>$AntennaMode);
    $filter=array('Result','Lock','StartTime','EndTime');
    return self::Call('MainTVAgent2','GetChannelLockInformation',$args,$filter);
  }
  // Instance:ui4
  public function GetContrast($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentContrast');
    return self::Call('RenderingControl','GetContrast',$args,$filter);
  }

  public function GetCurrentBrowserMode(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','BrowserMode');
    return self::Call('MainTVAgent2','GetCurrentBrowserMode',null,$filter);
  }

  public function GetCurrentBrowserURL(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','BrowserURL');
    return self::Call('MainTVAgent2','GetCurrentBrowserURL',null,$filter);
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

  public function GetCurrentExternalSource(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','CurrentExternalSource','ID');
    return self::Call('MainTVAgent2','GetCurrentExternalSource',null,$filter);
  }

  public function GetCurrentHTSSpeakerLayout(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','SpeakerLayout');
    return self::Call('MainTVAgent2','GetCurrentHTSSpeakerLayout',null,$filter);
  }

  public function GetCurrentMainTVChannel(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','CurrentChannel');
    return self::Call('MainTVAgent2','GetCurrentMainTVChannel',null,$filter);
  }

  public function GetCurrentProgramInformationURL(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','CurrentProgInfoURL');
    return self::Call('MainTVAgent2','GetCurrentProgramInformationURL',null,$filter);
  }

  public function GetCurrentTime(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','CurrentTime');
    return self::Call('MainTVAgent2','GetCurrentTime',null,$filter);
  }
  // Instance:ui4
  public function GetCurrentTransportActions($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('Actions');
    return self::Call('AVTransport','GetCurrentTransportActions',$args,$filter);
  }

  public function GetDTVInformation(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','DTVInformation');
    return self::Call('MainTVAgent2','GetDTVInformation',null,$filter);
  }
  // AntennaMode:ui4, Channel:string
  public function GetDetailChannelInformation($AntennaMode,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('Channel'=>$Channel,'AntennaMode'=>$AntennaMode);
    $filter=array('Result','DetailChannelInformation');
    return self::Call('MainTVAgent2','GetDetailChannelInformation',$args,$filter);
  }
  // AntennaMode:ui4, StartTime:string, Channel:string
  public function GetDetailProgramInformation($AntennaMode,$StartTime,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'Channel'=>$Channel,'StartTime'=>$StartTime);
    $filter=array('Result','DetailProgramInformation');
    return self::Call('MainTVAgent2','GetDetailProgramInformation',$args,$filter);
  }
  // Instance:ui4
  public function GetDeviceCapabilities($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('PlayMedia','RecMedia','RecQualityModes');
    return self::Call('AVTransport','GetDeviceCapabilities',$args,$filter);
  }
  // Keyword:string
  public function GetFilteredProgarmURL($Keyword){
    if (!$this->GetOnlineState()) return null;
    $args=array('Keyword'=>$Keyword);
    $filter=array('Result','FilteredProgramURL');
    return self::Call('MainTVAgent2','GetFilteredProgarmURL',$args,$filter);
  }

  public function GetHTSAllSpeakerDistance(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','MaxDistance','AllSpeakerDistance');
    return self::Call('MainTVAgent2','GetHTSAllSpeakerDistance',null,$filter);
  }

  public function GetHTSAllSpeakerLevel(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','MaxLevel','AllSpeakerLevel');
    return self::Call('MainTVAgent2','GetHTSAllSpeakerLevel',null,$filter);
  }

  public function GetHTSSoundEffect(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','SoundEffect','SoundEffectList');
    return self::Call('MainTVAgent2','GetHTSSoundEffect',null,$filter);
  }

  public function GetHTSSpeakerConfig(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','SpeakerChannel','SpeakerLFE');
    return self::Call('MainTVAgent2','GetHTSSpeakerConfig',null,$filter);
  }

  public function GetMBRDeviceList(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','MBRDeviceList');
    return self::Call('MainTVAgent2','GetMBRDeviceList',null,$filter);
  }

  public function GetMBRDongleStatus(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','MBRDongleStatus');
    return self::Call('MainTVAgent2','GetMBRDongleStatus',null,$filter);
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

  public function GetRecordChannel(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','RecordChannel','RecordChannel2');
    return self::Call('MainTVAgent2','GetRecordChannel',null,$filter);
  }
  // AntennaMode:ui4, Channel:string
  public function GetRegionalVariantList($AntennaMode,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'Channel'=>$Channel);
    $filter=array('Result','RegionalVariantList');
    return self::Call('MainTVAgent2','GetRegionalVariantList',$args,$filter);
  }
  // Instance:ui4
  public function GetRepeat($Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    return $this->_boRepeat;
  }

  public function GetScheduleListURL(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','ScheduleListURL');
    return self::Call('MainTVAgent2','GetScheduleListURL',null,$filter);
  }
  // Instance:ui4
  public function GetSharpness($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentSharpness');
    return self::Call('RenderingControl','GetSharpness',$args,$filter);
  }
  // Instance:ui4
  public function GetShuffle($Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    return $this->_boShuffle;
  }

  public function GetSourceList(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result','SourceList');
    return self::Call('MainTVAgent2','GetSourceList',null,$filter);
  }
  // Instance:ui4
  public function GetState($Instance=0) {
    $states=array('STOPPED'=>RPC2SAMSUNG_STATE_STOP,'PAUSED_PLAYBACK'=>RPC2SAMSUNG_STATE_PAUSE,'PLAYING'=>RPC2SAMSUNG_STATE_PLAY,'TRANSITIONING'=>RPC2SAMSUNG_STATE_TRANS,'NO_MEDIA_PRESENT'=>RPC2SAMSUNG_STATE_ERROR);
    $v=self::GetTransportInfo($Instance);
    return ($v&&($s=$v['CurrentTransportState'])&&isset($a[$s]))?$a[$s]:RPC2SAMSUNG_STATE_ERROR;
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
  // AntennaMode:ui4, ChannelName:string, Channel:string
  public function ModifyChannelName($AntennaMode,$ChannelName,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'Channel'=>$Channel,'ChannelName'=>$ChannelName);
    $filter=array('Result','ReturnChannelName');
    return self::Call('MainTVAgent2','ModifyChannelName',$args,$filter);
  }
  // AntennaMode:ui4, FavoriteChList:string
  public function ModifyFavoriteChannel($AntennaMode,$FavoriteChList){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'FavoriteChList'=>$FavoriteChList);
    $filter=array('Result');
    return self::Call('MainTVAgent2','ModifyFavoriteChannel',$args,$filter);
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
  // UID:ui4
  public function PlayRecordedItem($UID){
    if (!$this->GetOnlineState()) return null;
    $args=array('UID'=>$UID);
    $filter=array('Result');
    return self::Call('MainTVAgent2','PlayRecordedItem',$args,$filter);
  }
  // RemoteProtocolInfo:string, PeerConnectionManager:string, PeerConnectionID:i4, Direction:string
  public function PrepareForConnection($RemoteProtocolInfo,$PeerConnectionManager,$PeerConnectionID,$Direction){
    if (!$this->GetOnlineState()) return null;
    $args=array('RemoteProtocolInfo'=>$RemoteProtocolInfo,'PeerConnectionManager'=>$PeerConnectionManager,'PeerConnectionID'=>$PeerConnectionID,'Direction'=>$Direction);
    $filter=array('ConnectionID','AVTransportID','RcsID');
    return self::Call('ConnectionManager','PrepareForConnection',$args,$filter);
  }
  // Instance:ui4
  public function Previous($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Previous',$args,null);
  }
  // BrowserURL:string
  public function RunBrowser($BrowserURL){
    if (!$this->GetOnlineState()) return null;
    $args=array('BrowserURL'=>$BrowserURL);
    $filter=array('Result');
    return self::Call('MainTVAgent2','RunBrowser',$args,$filter);
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
  // BrowserCommand:string
  public function SendBrowserCommand($BrowserCommand){
    if (!$this->GetOnlineState()) return null;
    $args=array('BrowserCommand'=>$BrowserCommand);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SendBrowserCommand',$args,$filter);
  }
  // KeyCode:ui4, KeyDescription:string
  public function SendKeyCodeMultiScreenService($KeyCode,$KeyDescription){
    if (!$this->GetOnlineState()) return null;
    $args=array('KeyCode'=>$KeyCode,'KeyDescription'=>$KeyDescription);
    return self::Call('MultiScreenService','SendKeyCode',$args,null);
  }
  // KeyCode:ui4, KeyDescription:string
  public function SendKeyCodedial($KeyCode,$KeyDescription){
    if (!$this->GetOnlineState()) return null;
    $args=array('KeyCode'=>$KeyCode,'KeyDescription'=>$KeyDescription);
    return self::Call('dial','SendKeyCode',$args,null);
  }
  // ActivityIndex:ui4, MBRDevice:string, MBRIRKey:string
  public function SendMBRIRKey($ActivityIndex,$MBRDevice,$MBRIRKey){
    if (!$this->GetOnlineState()) return null;
    $args=array('ActivityIndex'=>$ActivityIndex,'MBRDevice'=>$MBRDevice,'MBRIRKey'=>$MBRIRKey);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SendMBRIRKey',$args,$filter);
  }
  // AVOff:string
  public function SetAVOff($AVOff){
    if (!$this->GetOnlineState()) return null;
    $args=array('AVOff'=>$AVOff);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetAVOff',$args,$filter);
  }
  // CurrentURI:string, CurrentURIMetaData:string, Instance:ui4
  public function SetAVTransportURI($CurrentURI,$CurrentURIMetaData,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CurrentURI'=>$CurrentURI,'CurrentURIMetaData'=>$CurrentURIMetaData);
    return self::Call('AVTransport','SetAVTransportURI',$args,null);
  }
  // AntennaMode:ui4
  public function SetAntennaMode($AntennaMode){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetAntennaMode',$args,$filter);
  }
  // DesiredBrightness:ui2, Instance:ui4
  public function SetBrightness($DesiredBrightness,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredBrightness'=>$DesiredBrightness);
    return self::Call('RenderingControl','SetBrightness',$args,null);
  }
  // AntennaMode:ui4, ChannelList:string, Lock:string, PIN:string, StartTime:ui4, EndTime:ui4
  public function SetChannelLock($AntennaMode,$ChannelList,$Lock,$PIN,$StartTime,$EndTime){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'ChannelList'=>$ChannelList,'Lock'=>$Lock,'PIN'=>$PIN,'StartTime'=>$StartTime,'EndTime'=>$EndTime);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetChannelLock',$args,$filter);
  }
  // DesiredContrast:ui2, Instance:ui4
  public function SetContrast($DesiredContrast,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredContrast'=>$DesiredContrast);
    return self::Call('RenderingControl','SetContrast',$args,null);
  }
  // AllSpeakerDistance:string
  public function SetHTSAllSpeakerDistance($AllSpeakerDistance){
    if (!$this->GetOnlineState()) return null;
    $args=array('AllSpeakerDistance'=>$AllSpeakerDistance);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetHTSAllSpeakerDistance',$args,$filter);
  }
  // AllSpeakerLevel:string
  public function SetHTSAllSpeakerLevel($AllSpeakerLevel){
    if (!$this->GetOnlineState()) return null;
    $args=array('AllSpeakerLevel'=>$AllSpeakerLevel);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetHTSAllSpeakerLevel',$args,$filter);
  }
  // SoundEffect:string
  public function SetHTSSoundEffect($SoundEffect){
    if (!$this->GetOnlineState()) return null;
    $args=array('SoundEffect'=>$SoundEffect);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetHTSSoundEffect',$args,$filter);
  }
  // ChannelListType:string, SatelliteID:ui4, Channel:string
  public function SetMainTVChannel($ChannelListType,$SatelliteID,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('ChannelListType'=>$ChannelListType,'SatelliteID'=>$SatelliteID,'Channel'=>$Channel);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetMainTVChannel',$args,$filter);
  }
  // Source:string, ID:ui4, UiID:ui4
  public function SetMainTVSource($Source,$ID,$UiID){
    if (!$this->GetOnlineState()) return null;
    $args=array('Source'=>$Source,'ID'=>$ID,'UiID'=>$UiID);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetMainTVSource',$args,$filter);
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
  // Instance:ui4, NewPlayMode:string
  public function SetPlayMode($Instance=0,$NewPlayMode='NORMAL'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'NewPlayMode'=>$NewPlayMode);
    return self::Call('AVTransport','SetPlayMode',$args,null);
  }
  // RecordDuration:ui4, Channel:string
  public function SetRecordDuration($RecordDuration,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('Channel'=>$Channel,'RecordDuration'=>$RecordDuration);
    $filter=array('Result');
    return self::Call('MainTVAgent2','SetRecordDuration',$args,$filter);
  }
  // AntennaMode:ui4, Channel:string
  public function SetRegionalVariant($AntennaMode,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'Channel'=>$Channel);
    $filter=array('Result','LogicalNumber');
    return self::Call('MainTVAgent2','SetRegionalVariant',$args,$filter);
  }
  // Repeat:boolean, Instance:ui4
  public function SetRepeat($Repeat,$Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($InstanceID);
    $this->SetPlayModes($this->_PlayModes[$this->_boRepeat=$boRepeat][$this->_boShuffle][$this->_boAll]);
  }
  // DesiredSharpness:ui2, Instance:ui4
  public function SetSharpness($DesiredSharpness,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredSharpness'=>$DesiredSharpness);
    return self::Call('RenderingControl','SetSharpness',$args,null);
  }
  // Shuffle:boolean, Instance:ui4
  public function SetShuffle($Shuffle,$Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    $this->SetPlayMode($this->_PlayModes[$this->_boRepeat][$this->_boShuffle=$boShuffle][$this->_boAll]);
  }
  // NewState:ui2, Instance:ui4
  public function SetState($NewState,$Instance=0) {
    switch($NewState){
      case RPC2SAMSUNG_STATE_STOP : $s=$this->Stop($Instance)?RPC2SAMSUNG_STATE_STOP:RPC2SAMSUNG_STATE_ERROR; break;
      case RPC2SAMSUNG_STATE_PREV : $s=$this->Previous($Instance)?RPC2SAMSUNG_STATE_PLAY:RPC2SAMSUNG_STATE_STOP;break;
      case RPC2SAMSUNG_STATE_PLAY : $s=$this->Play($Instance)?RPC2SAMSUNG_STATE_PLAY:RPC2SAMSUNG_STATE_STOP; break;
      case RPC2SAMSUNG_STATE_PAUSE: $s=$this->Pause($Instance)?RPC2SAMSUNG_STATE_PAUSE:RPC2SAMSUNG_STATE_STOP;break;
      case RPC2SAMSUNG_STATE_NEXT : $s=$this->Next($Instance)?RPC2SAMSUNG_STATE_PLAY:RPC2SAMSUNG_STATE_STOP; break;
      default : return RPC2SAMSUNG_STATE_ERROR;
    }
    return $s;
  }
  // DesiredVolume:ui2, Instance:ui4, Channel:string
  public function SetVolume($DesiredVolume,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'DesiredVolume'=>$DesiredVolume);
    return self::Call('RenderingControl','SetVolume',$args,null);
  }
  // ForcedFlag:string, DRMType:string
  public function StartCloneView($ForcedFlag,$DRMType){
    if (!$this->GetOnlineState()) return null;
    $args=array('ForcedFlag'=>$ForcedFlag,'DRMType'=>$DRMType);
    $filter=array('Result','CloneViewURL');
    return self::Call('MainTVAgent2','StartCloneView',$args,$filter);
  }
  // Source:string, ID:ui4, ForcedFlag:string, DRMType:string
  public function StartExtSourceView($Source,$ID,$ForcedFlag,$DRMType){
    if (!$this->GetOnlineState()) return null;
    $args=array('Source'=>$Source,'ID'=>$ID,'ForcedFlag'=>$ForcedFlag,'DRMType'=>$DRMType);
    $filter=array('Result','ExtSourceViewURL');
    return self::Call('MainTVAgent2','StartExtSourceView',$args,$filter);
  }
  // Channel:string
  public function StartInstantRecording($Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('Channel'=>$Channel);
    $filter=array('Result');
    return self::Call('MainTVAgent2','StartInstantRecording',$args,$filter);
  }
  // AntennaMode:ui4, ChannelListType:string, SatelliteID:ui4, ForcedFlag:string, DRMType:string, Channel:string
  public function StartSecondTVView($AntennaMode,$ChannelListType,$SatelliteID,$ForcedFlag,$DRMType,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('AntennaMode'=>$AntennaMode,'ChannelListType'=>$ChannelListType,'SatelliteID'=>$SatelliteID,'Channel'=>$Channel,'ForcedFlag'=>$ForcedFlag,'DRMType'=>$DRMType);
    $filter=array('Result','SecondTVURL');
    return self::Call('MainTVAgent2','StartSecondTVView',$args,$filter);
  }
  // Instance:ui4
  public function Stop($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Stop',$args,null);
  }

  public function StopBrowser(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Result');
    return self::Call('MainTVAgent2','StopBrowser',null,$filter);
  }
  // Channel:string
  public function StopRecord($Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('Channel'=>$Channel);
    $filter=array('Result');
    return self::Call('MainTVAgent2','StopRecord',$args,$filter);
  }
  // ViewURL:string
  public function StopView($ViewURL){
    if (!$this->GetOnlineState()) return null;
    $args=array('ViewURL'=>$ViewURL);
    $filter=array('Result');
    return self::Call('MainTVAgent2','StopView',$args,$filter);
  }
  // Instance:ui4
  protected function UpdatePlayMode($Instance=0){
    static $modes=array(
        'NORMAL'=>array(false,false,false),
    );
    if(empty($this->_PlayModes))
      foreach($modes as $k=>$a)$this->_PlayModes[$a[0]][$a[1]][$a[2]]=$k;
    if(!$t=$this->GetTransportSettings($Instance))return false;
    list($this->_boRepeat,$this->_boShuffle,$this->_boAll)=$modes[$t['PlayMode']];
  }
  // Instance:ui4
  public function X_DLNA_GetBytePositionInfo($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('TrackSize','RelByte','AbsByte');
    return self::Call('AVTransport','X_DLNA_GetBytePositionInfo',$args,$filter);
  }
  // Instance:ui4
  public function X_GetAudioSelection($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('AudioPID','AudioEncoding');
    return self::Call('RenderingControl','X_GetAudioSelection',$args,$filter);
  }
  // Instance:ui4
  public function X_GetStoppedReason($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('StoppedReason','StoppedReasonData');
    return self::Call('AVTransport','X_GetStoppedReason',$args,$filter);
  }
  // Instance:ui4
  public function X_GetVideoSelection($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('VideoPID','VideoEncoding');
    return self::Call('RenderingControl','X_GetVideoSelection',$args,$filter);
  }
  // AutoSlideShowMode:string, Instance:ui4
  public function X_SetAutoSlideShowMode($AutoSlideShowMode,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'AutoSlideShowMode'=>$AutoSlideShowMode);
    return self::Call('AVTransport','X_SetAutoSlideShowMode',$args,null);
  }
  // SlideShowEffectHint:string, Instance:ui4
  public function X_SetSlideShowEffectHint($SlideShowEffectHint,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'SlideShowEffectHint'=>$SlideShowEffectHint);
    return self::Call('AVTransport','X_SetSlideShowEffectHint',$args,null);
  }
  // AudioPID:ui2, AudioEncoding:string, Instance:ui4
  public function X_UpdateAudioSelection($AudioPID,$AudioEncoding,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'AudioPID'=>$AudioPID,'AudioEncoding'=>$AudioEncoding);
    return self::Call('RenderingControl','X_UpdateAudioSelection',$args,null);
  }
  // VideoPID:ui2, VideoEncoding:string, Instance:ui4
  public function X_UpdateVideoSelection($VideoPID,$VideoEncoding,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'VideoPID'=>$VideoPID,'VideoEncoding'=>$VideoEncoding);
    return self::Call('RenderingControl','X_UpdateVideoSelection',$args,null);
  }
}
?>