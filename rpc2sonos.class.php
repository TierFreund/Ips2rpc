<?
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 |  Class           :rpc2sonos extends uRpcDevice                                 |
 |  Version         :2.2                                                          |
 |  BuildDate       :Tue 19.01.2016 11:21:28                                      |
 |  Publisher       :(c)2016 Xaver Bauer                                          |
 |  Contact         :xaver65@gmail.com                                            |
 |  Desc            :PHP Classes to Control Sonos PLAY:3                          |
 |  port            :1400                                                         |
 |  base            :http://192.168.112.54:1400                                   |
 |  scpdurl         :/xml/device_description.xml                                  |
 |  modelName       :Sonos PLAY:3                                                 |
 |  deviceType      :urn:schemas-upnp-org:device:ZonePlayer:1                     |
 |  friendlyName    :192.168.112.54 - Sonos PLAY:3                                |
 |  manufacturer    :Sonos, Inc.                                                  |
 |  manufacturerURL :http://www.sonos.com                                         |
 |  modelNumber     :S3                                                           |
 |  modelURL        :http://www.sonos.com/products/zoneplayers/S3                 |
 |  UDN             :uuid:RINCON_B8E9373DABCE01400                                |
 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

if (!DEFINED('RPC2SONOS_STATE_STOP')) {
  DEFINE('RPC2SONOS_STATE_STOP',0);
  DEFINE('RPC2SONOS_STATE_PREV',1);
  DEFINE('RPC2SONOS_STATE_PLAY',2);
  DEFINE('RPC2SONOS_STATE_PAUSE',3);
  DEFINE('RPC2SONOS_STATE_NEXT',4);
  DEFINE('RPC2SONOS_STATE_TRANS',5);
  DEFINE('RPC2SONOS_STATE_ERROR',6);
}
class rpc2sonos extends RpcDevice {
  protected $_boRepeat=false;
  protected $_boShuffle=false;
  protected $_boAll=false;
  // Name:string
  protected function GetServiceConnData($name){
    switch($name){
      case                     'AlarmClock' : return [1400,"urn:schemas-upnp-org:service:AlarmClock:1","/AlarmClock/Control","/AlarmClock/Event","/xml/AlarmClock1.xml"];
      case                  'MusicServices' : return [1400,"urn:schemas-upnp-org:service:MusicServices:1","/MusicServices/Control","/MusicServices/Event","/xml/MusicServices1.xml"];
      case               'DeviceProperties' : return [1400,"urn:schemas-upnp-org:service:DeviceProperties:1","/DeviceProperties/Control","/DeviceProperties/Event","/xml/DeviceProperties1.xml"];
      case               'SystemProperties' : return [1400,"urn:schemas-upnp-org:service:SystemProperties:1","/SystemProperties/Control","/SystemProperties/Event","/xml/SystemProperties1.xml"];
      case              'ZoneGroupTopology' : return [1400,"urn:schemas-upnp-org:service:ZoneGroupTopology:1","/ZoneGroupTopology/Control","/ZoneGroupTopology/Event","/xml/ZoneGroupTopology1.xml"];
      case                'GroupManagement' : return [1400,"urn:schemas-upnp-org:service:GroupManagement:1","/GroupManagement/Control","/GroupManagement/Event","/xml/GroupManagement1.xml"];
      case                          'QPlay' : return [1400,"urn:schemas-tencent-com:service:QPlay:1","/QPlay/Control","/QPlay/Event","/xml/QPlay1.xml"];
      case               'ContentDirectory' : return [1400,"urn:schemas-upnp-org:service:ContentDirectory:1","/MediaServer/ContentDirectory/Control","/MediaServer/ContentDirectory/Event","/xml/ContentDirectory1.xml"];
      case   'ConnectionManagerMediaServer' : return [1400,"urn:schemas-upnp-org:service:ConnectionManager:1","/MediaServer/ConnectionManager/Control","/MediaServer/ConnectionManager/Event","/xml/ConnectionManager1.xml"];
      case               'RenderingControl' : return [1400,"urn:schemas-upnp-org:service:RenderingControl:1","/MediaRenderer/RenderingControl/Control","/MediaRenderer/RenderingControl/Event","/xml/RenderingControl1.xml"];
      case 'ConnectionManagerMediaRenderer' : return [1400,"urn:schemas-upnp-org:service:ConnectionManager:1","/MediaRenderer/ConnectionManager/Control","/MediaRenderer/ConnectionManager/Event","/xml/ConnectionManager1.xml"];
      case                    'AVTransport' : return [1400,"urn:schemas-upnp-org:service:AVTransport:1","/MediaRenderer/AVTransport/Control","/MediaRenderer/AVTransport/Event","/xml/AVTransport1.xml"];
      case                          'Queue' : return [1400,"urn:schemas-sonos-com:service:Queue:1","/MediaRenderer/Queue/Control","/MediaRenderer/Queue/Event","/xml/Queue1.xml"];
      case          'GroupRenderingControl' : return [1400,"urn:schemas-upnp-org:service:GroupRenderingControl:1","/MediaRenderer/GroupRenderingControl/Control","/MediaRenderer/GroupRenderingControl/Event","/xml/GroupRenderingControl1.xml"];
    }
    return null;
  }
  // url:string, defaultPort:ui4, requestType:string
  public function __construct($url, $defaultPort=1400, $requestType='curl'){
    parent::__construct($url,$defaultPort?$defaultPort:1400,$requestType);
  }
  // AccountType:ui4, AccountToken:string, AccountKey:string
  public function AddAccountWithCredentialsX($AccountType,$AccountToken,$AccountKey){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountToken'=>$AccountToken,'AccountKey'=>$AccountKey);
    return self::Call('SystemProperties','AddAccountWithCredentialsX',$args,null);
  }
  // AccountType:ui4, AccountID:string, AccountPassword:string
  public function AddAccountX($AccountType,$AccountID,$AccountPassword){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountID'=>$AccountID,'AccountPassword'=>$AccountPassword);
    $filter=array('AccountUDN');
    return self::Call('SystemProperties','AddAccountX',$args,$filter);
  }
  // ChannelMapSet:string
  public function AddBondedZones($ChannelMapSet){
    if (!$this->GetOnlineState()) return null;
    $args=array('ChannelMapSet'=>$ChannelMapSet);
    return self::Call('DeviceProperties','AddBondedZones',$args,null);
  }
  // HTSatChanMapSet:string
  public function AddHTSatellite($HTSatChanMapSet){
    if (!$this->GetOnlineState()) return null;
    $args=array('HTSatChanMapSet'=>$HTSatChanMapSet);
    return self::Call('DeviceProperties','AddHTSatellite',$args,null);
  }
  // MemberID:string, BootSeq:ui4
  public function AddMember($MemberID,$BootSeq){
    if (!$this->GetOnlineState()) return null;
    $args=array('MemberID'=>$MemberID,'BootSeq'=>$BootSeq);
    $filter=array('CurrentTransportSettings','GroupUUIDJoined','ResetVolumeAfter','VolumeAVTransportURI');
    return self::Call('GroupManagement','AddMember',$args,$filter);
  }
  // QueueID:ui4, UpdateID:ui4, ContainerURI:string, ContainerMetaData:string, DesiredFirstTrackNumberEnqueued:ui4, EnqueueAsNext:boolean, NumberOfURIs:ui4, EnqueuedURIsAndMetaData:string
  public function AddMultipleURIs($QueueID,$UpdateID,$ContainerURI,$ContainerMetaData,$DesiredFirstTrackNumberEnqueued,$EnqueueAsNext,$NumberOfURIs,$EnqueuedURIsAndMetaData){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'UpdateID'=>$UpdateID,'ContainerURI'=>$ContainerURI,'ContainerMetaData'=>$ContainerMetaData,'DesiredFirstTrackNumberEnqueued'=>$DesiredFirstTrackNumberEnqueued,'EnqueueAsNext'=>$EnqueueAsNext,'NumberOfURIs'=>$NumberOfURIs,'EnqueuedURIsAndMetaData'=>$EnqueuedURIsAndMetaData);
    $filter=array('FirstTrackNumberEnqueued','NumTracksAdded','NewQueueLength','NewUpdateID');
    return self::Call('Queue','AddMultipleURIs',$args,$filter);
  }
  // UpdateID:ui4, NumberOfURIs:ui4, EnqueuedURIs:string, EnqueuedURIsMetaData:string, ContainerURI:string, ContainerMetaData:string, DesiredFirstTrackNumberEnqueued:ui4, EnqueueAsNext:boolean, Instance:ui4
  public function AddMultipleURIsToQueue($UpdateID,$NumberOfURIs,$EnqueuedURIs,$EnqueuedURIsMetaData,$ContainerURI,$ContainerMetaData,$DesiredFirstTrackNumberEnqueued,$EnqueueAsNext,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'UpdateID'=>$UpdateID,'NumberOfURIs'=>$NumberOfURIs,'EnqueuedURIs'=>$EnqueuedURIs,'EnqueuedURIsMetaData'=>$EnqueuedURIsMetaData,'ContainerURI'=>$ContainerURI,'ContainerMetaData'=>$ContainerMetaData,'DesiredFirstTrackNumberEnqueued'=>$DesiredFirstTrackNumberEnqueued,'EnqueueAsNext'=>$EnqueueAsNext);
    $filter=array('FirstTrackNumberEnqueued','NumTracksAdded','NewQueueLength','NewUpdateID');
    return self::Call('AVTransport','AddMultipleURIsToQueue',$args,$filter);
  }
  // AccountType:ui4, AccountToken:string, AccountKey:string, OAuthDeviceID:string
  public function AddOAuthAccountX($AccountType,$AccountToken,$AccountKey,$OAuthDeviceID){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountToken'=>$AccountToken,'AccountKey'=>$AccountKey,'OAuthDeviceID'=>$OAuthDeviceID);
    $filter=array('AccountUDN');
    return self::Call('SystemProperties','AddOAuthAccountX',$args,$filter);
  }
  // QueueID:ui4, UpdateID:ui4, EnqueuedURI:string, EnqueuedURIMetaData:string, DesiredFirstTrackNumberEnqueued:ui4, EnqueueAsNext:boolean
  public function AddURI($QueueID,$UpdateID,$EnqueuedURI,$EnqueuedURIMetaData,$DesiredFirstTrackNumberEnqueued,$EnqueueAsNext){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'UpdateID'=>$UpdateID,'EnqueuedURI'=>$EnqueuedURI,'EnqueuedURIMetaData'=>$EnqueuedURIMetaData,'DesiredFirstTrackNumberEnqueued'=>$DesiredFirstTrackNumberEnqueued,'EnqueueAsNext'=>$EnqueueAsNext);
    $filter=array('FirstTrackNumberEnqueued','NumTracksAdded','NewQueueLength','NewUpdateID');
    return self::Call('Queue','AddURI',$args,$filter);
  }
  // EnqueuedURI:string, EnqueuedURIMetaData:string, DesiredFirstTrackNumberEnqueued:ui4, EnqueueAsNext:boolean, Instance:ui4
  public function AddURIToQueue($EnqueuedURI,$EnqueuedURIMetaData,$DesiredFirstTrackNumberEnqueued,$EnqueueAsNext,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'EnqueuedURI'=>$EnqueuedURI,'EnqueuedURIMetaData'=>$EnqueuedURIMetaData,'DesiredFirstTrackNumberEnqueued'=>$DesiredFirstTrackNumberEnqueued,'EnqueueAsNext'=>$EnqueueAsNext);
    $filter=array('FirstTrackNumberEnqueued','NumTracksAdded','NewQueueLength');
    return self::Call('AVTransport','AddURIToQueue',$args,$filter);
  }
  // ObjectID:string, UpdateID:ui4, EnqueuedURI:string, EnqueuedURIMetaData:string, AddAtIndex:ui4, Instance:ui4
  public function AddURIToSavedQueue($ObjectID,$UpdateID,$EnqueuedURI,$EnqueuedURIMetaData,$AddAtIndex,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'ObjectID'=>$ObjectID,'UpdateID'=>$UpdateID,'EnqueuedURI'=>$EnqueuedURI,'EnqueuedURIMetaData'=>$EnqueuedURIMetaData,'AddAtIndex'=>$AddAtIndex);
    $filter=array('NumTracksAdded','NewQueueLength','NewUpdateID');
    return self::Call('AVTransport','AddURIToSavedQueue',$args,$filter);
  }
  // QueueOwnerID:string
  public function AttachQueue($QueueOwnerID){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueOwnerID'=>$QueueOwnerID);
    $filter=array('QueueID','QueueOwnerContext');
    return self::Call('Queue','AttachQueue',$args,$filter);
  }

  public function Backup(){
    if (!$this->GetOnlineState()) return null;
    return self::Call('Queue','Backup',null,null);
  }
  // Instance:ui4
  public function BackupQueue($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','BackupQueue',$args,null);
  }
  // Instance:ui4
  public function BecomeCoordinatorOfStandaloneGroup($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','BecomeCoordinatorOfStandaloneGroup',$args,null);
  }
  // CurrentCoordinator:string, CurrentGroupID:string, OtherMembers:string, TransportSettings:string, CurrentURI:string, CurrentURIMetaData:string, SleepTimerState:string, AlarmState:string, StreamRestartState:string, CurrentQueueTrackList:string, Instance:ui4
  public function BecomeGroupCoordinator($CurrentCoordinator,$CurrentGroupID,$OtherMembers,$TransportSettings,$CurrentURI,$CurrentURIMetaData,$SleepTimerState,$AlarmState,$StreamRestartState,$CurrentQueueTrackList,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CurrentCoordinator'=>$CurrentCoordinator,'CurrentGroupID'=>$CurrentGroupID,'OtherMembers'=>$OtherMembers,'TransportSettings'=>$TransportSettings,'CurrentURI'=>$CurrentURI,'CurrentURIMetaData'=>$CurrentURIMetaData,'SleepTimerState'=>$SleepTimerState,'AlarmState'=>$AlarmState,'StreamRestartState'=>$StreamRestartState,'CurrentQueueTrackList'=>$CurrentQueueTrackList);
    return self::Call('AVTransport','BecomeGroupCoordinator',$args,null);
  }
  // CurrentCoordinator:string, CurrentGroupID:string, OtherMembers:string, CurrentURI:string, CurrentURIMetaData:string, SleepTimerState:string, AlarmState:string, StreamRestartState:string, CurrentAVTTrackList:string, CurrentQueueTrackList:string, CurrentSourceState:string, ResumePlayback:boolean, Instance:ui4
  public function BecomeGroupCoordinatorAndSource($CurrentCoordinator,$CurrentGroupID,$OtherMembers,$CurrentURI,$CurrentURIMetaData,$SleepTimerState,$AlarmState,$StreamRestartState,$CurrentAVTTrackList,$CurrentQueueTrackList,$CurrentSourceState,$ResumePlayback,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CurrentCoordinator'=>$CurrentCoordinator,'CurrentGroupID'=>$CurrentGroupID,'OtherMembers'=>$OtherMembers,'CurrentURI'=>$CurrentURI,'CurrentURIMetaData'=>$CurrentURIMetaData,'SleepTimerState'=>$SleepTimerState,'AlarmState'=>$AlarmState,'StreamRestartState'=>$StreamRestartState,'CurrentAVTTrackList'=>$CurrentAVTTrackList,'CurrentQueueTrackList'=>$CurrentQueueTrackList,'CurrentSourceState'=>$CurrentSourceState,'ResumePlayback'=>$ResumePlayback);
    return self::Call('AVTransport','BecomeGroupCoordinatorAndSource',$args,null);
  }
  // UpdateURL:string, Flags:ui4, ExtraOptions:string
  public function BeginSoftwareUpdate($UpdateURL,$Flags,$ExtraOptions){
    if (!$this->GetOnlineState()) return null;
    $args=array('UpdateURL'=>$UpdateURL,'Flags'=>$Flags,'ExtraOptions'=>$ExtraOptions);
    return self::Call('ZoneGroupTopology','BeginSoftwareUpdate',$args,null);
  }
  // ObjectID:string, BrowseFlag:string, Filter:string, StartingIndex:ui4, RequestedCount:ui4, SortCriteria:string
  public function BrowseContentDirectory($ObjectID,$BrowseFlag,$Filter,$StartingIndex,$RequestedCount,$SortCriteria){
    if (!$this->GetOnlineState()) return null;
    $args=array('ObjectID'=>$ObjectID,'BrowseFlag'=>$BrowseFlag,'Filter'=>$Filter,'StartingIndex'=>$StartingIndex,'RequestedCount'=>$RequestedCount,'SortCriteria'=>$SortCriteria);
    $filter=array('Result','NumberReturned','TotalMatches','UpdateID');
    return self::Call('ContentDirectory','Browse',$args,$filter);
  }
  // QueueID:ui4, StartingIndex:ui4, RequestedCount:ui4
  public function BrowseQueue($QueueID,$StartingIndex,$RequestedCount){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'StartingIndex'=>$StartingIndex,'RequestedCount'=>$RequestedCount);
    $filter=array('Result','NumberReturned','TotalMatches','UpdateID');
    return self::Call('Queue','Browse',$args,$filter);
  }
  // CurrentCoordinator:string, NewCoordinator:string, NewTransportSettings:string, Instance:ui4
  public function ChangeCoordinator($CurrentCoordinator,$NewCoordinator,$NewTransportSettings,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CurrentCoordinator'=>$CurrentCoordinator,'NewCoordinator'=>$NewCoordinator,'NewTransportSettings'=>$NewTransportSettings);
    return self::Call('AVTransport','ChangeCoordinator',$args,null);
  }
  // NewTransportSettings:string, CurrentAVTransportURI:string, Instance:ui4
  public function ChangeTransportSettings($NewTransportSettings,$CurrentAVTransportURI,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'NewTransportSettings'=>$NewTransportSettings,'CurrentAVTransportURI'=>$CurrentAVTransportURI);
    return self::Call('AVTransport','ChangeTransportSettings',$args,null);
  }
  // UpdateType:string, CachedOnly:boolean, Version:string
  public function CheckForUpdate($UpdateType,$CachedOnly,$Version){
    if (!$this->GetOnlineState()) return null;
    $args=array('UpdateType'=>$UpdateType,'CachedOnly'=>$CachedOnly,'Version'=>$Version);
    $filter=array('UpdateItem');
    return self::Call('ZoneGroupTopology','CheckForUpdate',$args,$filter);
  }
  // NewSleepTimerDuration:string, Instance:ui4
  public function ConfigureSleepTimer($NewSleepTimerDuration,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'NewSleepTimerDuration'=>$NewSleepTimerDuration);
    return self::Call('AVTransport','ConfigureSleepTimer',$args,null);
  }
  // StartLocalTime:string, Duration:string, Recurrence:string, Enabled:boolean, RoomUUID:string, ProgramURI:string, ProgramMetaData:string, PlayMode:string, Volume:ui2, IncludeLinkedZones:boolean
  public function CreateAlarm($StartLocalTime,$Duration,$Recurrence,$Enabled,$RoomUUID,$ProgramURI,$ProgramMetaData,$PlayMode,$Volume,$IncludeLinkedZones){
    if (!$this->GetOnlineState()) return null;
    $args=array('StartLocalTime'=>$StartLocalTime,'Duration'=>$Duration,'Recurrence'=>$Recurrence,'Enabled'=>$Enabled,'RoomUUID'=>$RoomUUID,'ProgramURI'=>$ProgramURI,'ProgramMetaData'=>$ProgramMetaData,'PlayMode'=>$PlayMode,'Volume'=>$Volume,'IncludeLinkedZones'=>$IncludeLinkedZones);
    $filter=array('AssignedID');
    return self::Call('AlarmClock','CreateAlarm',$args,$filter);
  }
  // ContainerID:string, Elements:string
  public function CreateObject($ContainerID,$Elements){
    if (!$this->GetOnlineState()) return null;
    $args=array('ContainerID'=>$ContainerID,'Elements'=>$Elements);
    $filter=array('ObjectID','Result');
    return self::Call('ContentDirectory','CreateObject',$args,$filter);
  }
  // QueueOwnerID:string, QueueOwnerContext:string, QueuePolicy:string
  public function CreateQueue($QueueOwnerID,$QueueOwnerContext,$QueuePolicy){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueOwnerID'=>$QueueOwnerID,'QueueOwnerContext'=>$QueueOwnerContext,'QueuePolicy'=>$QueuePolicy);
    $filter=array('QueueID');
    return self::Call('Queue','CreateQueue',$args,$filter);
  }
  // Title:string, EnqueuedURI:string, EnqueuedURIMetaData:string, Instance:ui4
  public function CreateSavedQueue($Title,$EnqueuedURI,$EnqueuedURIMetaData,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Title'=>$Title,'EnqueuedURI'=>$EnqueuedURI,'EnqueuedURIMetaData'=>$EnqueuedURIMetaData);
    $filter=array('NumTracksAdded','NewQueueLength','AssignedObjectID','NewUpdateID');
    return self::Call('AVTransport','CreateSavedQueue',$args,$filter);
  }
  // ChannelMapSet:string
  public function CreateStereoPair($ChannelMapSet){
    if (!$this->GetOnlineState()) return null;
    $args=array('ChannelMapSet'=>$ChannelMapSet);
    return self::Call('DeviceProperties','CreateStereoPair',$args,null);
  }
  // NewCoordinator:string, RejoinGroup:boolean, Instance:ui4
  public function DelegateGroupCoordinationTo($NewCoordinator,$RejoinGroup,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'NewCoordinator'=>$NewCoordinator,'RejoinGroup'=>$RejoinGroup);
    return self::Call('AVTransport','DelegateGroupCoordinationTo',$args,null);
  }
  // ID:ui4
  public function DestroyAlarm($ID){
    if (!$this->GetOnlineState()) return null;
    $args=array('ID'=>$ID);
    return self::Call('AlarmClock','DestroyAlarm',$args,null);
  }
  // ObjectID:string
  public function DestroyObject($ObjectID){
    if (!$this->GetOnlineState()) return null;
    $args=array('ObjectID'=>$ObjectID);
    return self::Call('ContentDirectory','DestroyObject',$args,null);
  }

  public function DoPostUpdateTasks(){
    if (!$this->GetOnlineState()) return null;
    return self::Call('SystemProperties','DoPostUpdateTasks',null,null);
  }
  // AccountType:ui4, AccountID:string, NewAccountMd:string
  public function EditAccountMd($AccountType,$AccountID,$NewAccountMd){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountID'=>$AccountID,'NewAccountMd'=>$NewAccountMd);
    return self::Call('SystemProperties','EditAccountMd',$args,null);
  }
  // AccountType:ui4, AccountID:string, NewAccountPassword:string
  public function EditAccountPasswordX($AccountType,$AccountID,$NewAccountPassword){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountID'=>$AccountID,'NewAccountPassword'=>$NewAccountPassword);
    return self::Call('SystemProperties','EditAccountPasswordX',$args,null);
  }
  // RDMValue:boolean
  public function EnableRDM($RDMValue){
    if (!$this->GetOnlineState()) return null;
    $args=array('RDMValue'=>$RDMValue);
    return self::Call('SystemProperties','EnableRDM',$args,null);
  }
  // Mode:string, Options:string
  public function EnterConfigMode($Mode,$Options){
    if (!$this->GetOnlineState()) return null;
    $args=array('Mode'=>$Mode,'Options'=>$Options);
    $filter=array('State');
    return self::Call('DeviceProperties','EnterConfigMode',$args,$filter);
  }
  // Options:string
  public function ExitConfigMode($Options){
    if (!$this->GetOnlineState()) return null;
    $args=array('Options'=>$Options);
    return self::Call('DeviceProperties','ExitConfigMode',$args,null);
  }
  // ObjectID:string, Prefix:string
  public function FindPrefix($ObjectID,$Prefix){
    if (!$this->GetOnlineState()) return null;
    $args=array('ObjectID'=>$ObjectID,'Prefix'=>$Prefix);
    $filter=array('StartingIndex','UpdateID');
    return self::Call('ContentDirectory','FindPrefix',$args,$filter);
  }

  public function GetAlbumArtistDisplayOption(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('AlbumArtistDisplayOption');
    return self::Call('ContentDirectory','GetAlbumArtistDisplayOption',null,$filter);
  }
  // ObjectID:string
  public function GetAllPrefixLocations($ObjectID){
    if (!$this->GetOnlineState()) return null;
    $args=array('ObjectID'=>$ObjectID);
    $filter=array('TotalPrefixes','PrefixAndIndexCSV','UpdateID');
    return self::Call('ContentDirectory','GetAllPrefixLocations',$args,$filter);
  }

  public function GetAutoplayLinkedZones(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('IncludeLinkedZones');
    return self::Call('DeviceProperties','GetAutoplayLinkedZones',null,$filter);
  }

  public function GetAutoplayRoomUUID(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('RoomUUID');
    return self::Call('DeviceProperties','GetAutoplayRoomUUID',null,$filter);
  }

  public function GetAutoplayVolume(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentVolume');
    return self::Call('DeviceProperties','GetAutoplayVolume',null,$filter);
  }
  // Instance:ui4
  public function GetBass($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentBass');
    return self::Call('RenderingControl','GetBass',$args,$filter);
  }

  public function GetBrowseable(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('IsBrowseable');
    return self::Call('ContentDirectory','GetBrowseable',null,$filter);
  }

  public function GetButtonState(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('State');
    return self::Call('DeviceProperties','GetButtonState',null,$filter);
  }
  // Instance:ui4
  public function GetCrossfadeMode($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CrossfadeMode');
    return self::Call('AVTransport','GetCrossfadeMode',$args,$filter);
  }

  public function GetCurrentConnectionIDsConnectionManager(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('ConnectionIDs');
    return self::Call('ConnectionManagerMediaRenderer','GetCurrentConnectionIDs',null,$filter);
  }
  // ConnectionID:i4
  public function GetCurrentConnectionInfoConnectionManager($ConnectionID){
    if (!$this->GetOnlineState()) return null;
    $args=array('ConnectionID'=>$ConnectionID);
    $filter=array('RcsID','AVTransportID','ProtocolInfo','PeerConnectionManager','PeerConnectionID','Direction','Status');
    return self::Call('ConnectionManagerMediaRenderer','GetCurrentConnectionInfo',$args,$filter);
  }
  // Instance:ui4
  public function GetCurrentTransportActions($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('Actions');
    return self::Call('AVTransport','GetCurrentTransportActions',$args,$filter);
  }

  public function GetDailyIndexRefreshTime(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentDailyIndexRefreshTime');
    return self::Call('AlarmClock','GetDailyIndexRefreshTime',null,$filter);
  }
  // Instance:ui4
  public function GetDeviceCapabilities($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('PlayMedia','RecMedia','RecQualityModes');
    return self::Call('AVTransport','GetDeviceCapabilities',$args,$filter);
  }
  // EQType:string, Instance:ui4
  public function GetEQ($EQType,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'EQType'=>$EQType);
    $filter=array('CurrentValue');
    return self::Call('RenderingControl','GetEQ',$args,$filter);
  }

  public function GetFormat(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentTimeFormat','CurrentDateFormat');
    return self::Call('AlarmClock','GetFormat',null,$filter);
  }
  // Instance:ui4
  public function GetGroupMute($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentMute');
    return self::Call('GroupRenderingControl','GetGroupMute',$args,$filter);
  }
  // Instance:ui4
  public function GetGroupVolume($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentVolume');
    return self::Call('GroupRenderingControl','GetGroupVolume',$args,$filter);
  }
  // Instance:ui4
  public function GetHeadphoneConnected($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentHeadphoneConnected');
    return self::Call('RenderingControl','GetHeadphoneConnected',$args,$filter);
  }

  public function GetHouseholdID(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentHouseholdID');
    return self::Call('DeviceProperties','GetHouseholdID',null,$filter);
  }
  // TimeStamp:string
  public function GetHouseholdTimeAtStamp($TimeStamp){
    if (!$this->GetOnlineState()) return null;
    $args=array('TimeStamp'=>$TimeStamp);
    $filter=array('HouseholdUTCTime');
    return self::Call('AlarmClock','GetHouseholdTimeAtStamp',$args,$filter);
  }

  public function GetLEDState(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentLEDState');
    return self::Call('DeviceProperties','GetLEDState',null,$filter);
  }

  public function GetLastIndexChange(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('LastIndexChange');
    return self::Call('ContentDirectory','GetLastIndexChange',null,$filter);
  }
  // Instance:ui4, Channel:string
  public function GetLoudness($Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel);
    $filter=array('CurrentLoudness');
    return self::Call('RenderingControl','GetLoudness',$args,$filter);
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
  public function GetOutputFixed($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentFixed');
    return self::Call('RenderingControl','GetOutputFixed',$args,$filter);
  }
  // Instance:ui4
  public function GetPositionInfo($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('Track','TrackDuration','TrackMetaData','TrackURI','RelTime','AbsTime','RelCount','AbsCount');
    return self::Call('AVTransport','GetPositionInfo',$args,$filter);
  }

  public function GetProtocolInfoConnectionManager(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Source','Sink');
    return self::Call('ConnectionManagerMediaRenderer','GetProtocolInfo',null,$filter);
  }

  public function GetRDM(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('RDMValue');
    return self::Call('SystemProperties','GetRDM',null,$filter);
  }
  // Instance:ui4
  public function GetRemainingSleepTimerDuration($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('RemainingSleepTimerDuration','CurrentSleepTimerGeneration');
    return self::Call('AVTransport','GetRemainingSleepTimerDuration',$args,$filter);
  }
  // Instance:ui4
  public function GetRepeat($Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    return $this->_boRepeat;
  }
  // Instance:ui4
  public function GetRunningAlarmProperties($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('AlarmID','GroupID','LoggedStartTime');
    return self::Call('AVTransport','GetRunningAlarmProperties',$args,$filter);
  }

  public function GetSearchCapabilities(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('SearchCaps');
    return self::Call('ContentDirectory','GetSearchCapabilities',null,$filter);
  }
  // ServiceId:ui4, Username:string
  public function GetSessionId($ServiceId,$Username){
    if (!$this->GetOnlineState()) return null;
    $args=array('ServiceId'=>$ServiceId,'Username'=>$Username);
    $filter=array('SessionId');
    return self::Call('MusicServices','GetSessionId',$args,$filter);
  }

  public function GetShareIndexInProgress(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('IsIndexing');
    return self::Call('ContentDirectory','GetShareIndexInProgress',null,$filter);
  }
  // Instance:ui4
  public function GetShuffle($Instance=0) {
    if(empty($this->_PlayModes))$this->UpdatePlayMode($Instance);
    return $this->_boShuffle;
  }
  // Instance:ui4
  public function GetSonarStatus($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('SonarEnabled','SonarCalibrationAvailable');
    return self::Call('RenderingControl','GetSonarStatus',$args,$filter);
  }

  public function GetSortCapabilities(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('SortCaps');
    return self::Call('ContentDirectory','GetSortCapabilities',null,$filter);
  }
  // Instance:ui4
  public function GetState($Instance=0) {
    $states=array('STOPPED'=>RPC2SONOS_STATE_STOP,'PLAYING'=>RPC2SONOS_STATE_PLAY,'PAUSED_PLAYBACK'=>RPC2SONOS_STATE_PAUSE,'TRANSITIONING'=>RPC2SONOS_STATE_TRANS);
    $v=self::GetTransportInfo($Instance);
    return ($v&&($s=$v['CurrentTransportState'])&&isset($a[$s]))?$a[$s]:RPC2SONOS_STATE_ERROR;
  }
  // VariableName:string
  public function GetString($VariableName){
    if (!$this->GetOnlineState()) return null;
    $args=array('VariableName'=>$VariableName);
    $filter=array('StringValue');
    return self::Call('SystemProperties','GetString',$args,$filter);
  }
  // Instance:ui4
  public function GetSupportsOutputFixed($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentSupportsFixed');
    return self::Call('RenderingControl','GetSupportsOutputFixed',$args,$filter);
  }

  public function GetSystemUpdateID(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Id');
    return self::Call('ContentDirectory','GetSystemUpdateID',null,$filter);
  }

  public function GetTimeNow(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentUTCTime','CurrentLocalTime','CurrentTimeZone','CurrentTimeGeneration');
    return self::Call('AlarmClock','GetTimeNow',null,$filter);
  }

  public function GetTimeServer(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentTimeServer');
    return self::Call('AlarmClock','GetTimeServer',null,$filter);
  }

  public function GetTimeZone(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Index','AutoAdjustDst');
    return self::Call('AlarmClock','GetTimeZone',null,$filter);
  }

  public function GetTimeZoneAndRule(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('Index','AutoAdjustDst','CurrentTimeZone');
    return self::Call('AlarmClock','GetTimeZoneAndRule',null,$filter);
  }
  // Index:i4
  public function GetTimeZoneRule($Index){
    if (!$this->GetOnlineState()) return null;
    $args=array('Index'=>$Index);
    $filter=array('TimeZone');
    return self::Call('AlarmClock','GetTimeZoneRule',$args,$filter);
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
  // Instance:ui4
  public function GetTreble($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('CurrentTreble');
    return self::Call('RenderingControl','GetTreble',$args,$filter);
  }

  public function GetUseAutoplayVolume(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('UseVolume');
    return self::Call('DeviceProperties','GetUseAutoplayVolume',null,$filter);
  }
  // Instance:ui4, Channel:string
  public function GetVolume($Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel);
    $filter=array('CurrentVolume');
    return self::Call('RenderingControl','GetVolume',$args,$filter);
  }
  // Instance:ui4, Channel:string
  public function GetVolumeDB($Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel);
    $filter=array('CurrentVolume');
    return self::Call('RenderingControl','GetVolumeDB',$args,$filter);
  }
  // Instance:ui4, Channel:string
  public function GetVolumeDBRange($Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel);
    $filter=array('MinValue','MaxValue');
    return self::Call('RenderingControl','GetVolumeDBRange',$args,$filter);
  }
  // AccountType:ui4
  public function GetWebCode($AccountType){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType);
    $filter=array('WebCode');
    return self::Call('SystemProperties','GetWebCode',$args,$filter);
  }

  public function GetZoneAttributes(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentZoneName','CurrentIcon','CurrentConfiguration');
    return self::Call('DeviceProperties','GetZoneAttributes',null,$filter);
  }

  public function GetZoneGroupAttributes(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentZoneGroupName','CurrentZoneGroupID','CurrentZonePlayerUUIDsInGroup');
    return self::Call('ZoneGroupTopology','GetZoneGroupAttributes',null,$filter);
  }

  public function GetZoneGroupState(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('ZoneGroupState');
    return self::Call('ZoneGroupTopology','GetZoneGroupState',null,$filter);
  }

  public function GetZoneInfo(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('SerialNumber','SoftwareVersion','DisplaySoftwareVersion','HardwareVersion','IPAddress','MACAddress','CopyrightInfo','ExtraInfo','HTAudioIn');
    return self::Call('DeviceProperties','GetZoneInfo',null,$filter);
  }
  // SettingID:ui4, SettingURI:string
  public function ImportSetting($SettingID,$SettingURI){
    if (!$this->GetOnlineState()) return null;
    $args=array('SettingID'=>$SettingID,'SettingURI'=>$SettingURI);
    return self::Call('DeviceProperties','ImportSetting',$args,null);
  }

  public function ListAlarms(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('CurrentAlarmList','CurrentAlarmListVersion');
    return self::Call('AlarmClock','ListAlarms',null,$filter);
  }

  public function ListAvailableServices(){
    if (!$this->GetOnlineState()) return null;
    $filter=array('AvailableServiceDescriptorList','AvailableServiceTypeList','AvailableServiceListVersion');
    return self::Call('MusicServices','ListAvailableServices',null,$filter);
  }
  // TargetAccountType:ui4, TargetAccountID:string, TargetAccountPassword:string
  public function MigrateTrialAccountX($TargetAccountType,$TargetAccountID,$TargetAccountPassword){
    if (!$this->GetOnlineState()) return null;
    $args=array('TargetAccountType'=>$TargetAccountType,'TargetAccountID'=>$TargetAccountID,'TargetAccountPassword'=>$TargetAccountPassword);
    return self::Call('SystemProperties','MigrateTrialAccountX',$args,null);
  }
  // Instance:ui4
  public function Next($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Next',$args,null);
  }
  // Instance:ui4
  public function NextProgrammedRadioTracks($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','NextProgrammedRadioTracks',$args,null);
  }
  // DeletedURI:string, Instance:ui4
  public function NotifyDeletedURI($DeletedURI,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DeletedURI'=>$DeletedURI);
    return self::Call('AVTransport','NotifyDeletedURI',$args,null);
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
  // AccountType:ui4, AccountID:string, AccountPassword:string
  public function ProvisionCredentialedTrialAccountX($AccountType,$AccountID,$AccountPassword){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountID'=>$AccountID,'AccountPassword'=>$AccountPassword);
    $filter=array('IsExpired','AccountUDN');
    return self::Call('SystemProperties','ProvisionCredentialedTrialAccountX',$args,$filter);
  }
  // AccountType:ui4
  public function ProvisionTrialAccount($AccountType){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType);
    $filter=array('AccountUDN');
    return self::Call('SystemProperties','ProvisionTrialAccount',$args,$filter);
  }
  // Seed:string
  public function QPlayAuth($Seed){
    if (!$this->GetOnlineState()) return null;
    $args=array('Seed'=>$Seed);
    $filter=array('Code','MID','DID');
    return self::Call('QPlay','QPlayAuth',$args,$filter);
  }
  // RampType:string, DesiredVolume:ui2, ResetVolumeAfter:boolean, ProgramURI:string, Instance:ui4, Channel:string
  public function RampToVolume($RampType,$DesiredVolume,$ResetVolumeAfter,$ProgramURI,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'RampType'=>$RampType,'DesiredVolume'=>$DesiredVolume,'ResetVolumeAfter'=>$ResetVolumeAfter,'ProgramURI'=>$ProgramURI);
    $filter=array('RampTime');
    return self::Call('RenderingControl','RampToVolume',$args,$filter);
  }
  // AccountType:ui4, AccountUID:ui4, AccountToken:string, AccountKey:string
  public function RefreshAccountCredentialsX($AccountType,$AccountUID,$AccountToken,$AccountKey){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountUID'=>$AccountUID,'AccountToken'=>$AccountToken,'AccountKey'=>$AccountKey);
    return self::Call('SystemProperties','RefreshAccountCredentialsX',$args,null);
  }
  // AlbumArtistDisplayOption:string
  public function RefreshShareIndex($AlbumArtistDisplayOption){
    if (!$this->GetOnlineState()) return null;
    $args=array('AlbumArtistDisplayOption'=>$AlbumArtistDisplayOption);
    return self::Call('ContentDirectory','RefreshShareIndex',$args,null);
  }
  // MobileDeviceName:string, MobileDeviceUDN:string, MobileIPAndPort:string
  public function RegisterMobileDevice($MobileDeviceName,$MobileDeviceUDN,$MobileIPAndPort){
    if (!$this->GetOnlineState()) return null;
    $args=array('MobileDeviceName'=>$MobileDeviceName,'MobileDeviceUDN'=>$MobileDeviceUDN,'MobileIPAndPort'=>$MobileIPAndPort);
    return self::Call('ZoneGroupTopology','RegisterMobileDevice',$args,null);
  }
  // VariableName:string
  public function Remove($VariableName){
    if (!$this->GetOnlineState()) return null;
    $args=array('VariableName'=>$VariableName);
    return self::Call('SystemProperties','Remove',$args,null);
  }
  // AccountType:ui4, AccountID:string
  public function RemoveAccount($AccountType,$AccountID){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountType'=>$AccountType,'AccountID'=>$AccountID);
    return self::Call('SystemProperties','RemoveAccount',$args,null);
  }
  // QueueID:ui4, UpdateID:ui4
  public function RemoveAllTracks($QueueID,$UpdateID){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'UpdateID'=>$UpdateID);
    $filter=array('NewUpdateID');
    return self::Call('Queue','RemoveAllTracks',$args,$filter);
  }
  // Instance:ui4
  public function RemoveAllTracksFromQueue($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','RemoveAllTracksFromQueue',$args,null);
  }
  // ChannelMapSet:string
  public function RemoveBondedZones($ChannelMapSet){
    if (!$this->GetOnlineState()) return null;
    $args=array('ChannelMapSet'=>$ChannelMapSet);
    return self::Call('DeviceProperties','RemoveBondedZones',$args,null);
  }
  // SatRoomUUID:string
  public function RemoveHTSatellite($SatRoomUUID){
    if (!$this->GetOnlineState()) return null;
    $args=array('SatRoomUUID'=>$SatRoomUUID);
    return self::Call('DeviceProperties','RemoveHTSatellite',$args,null);
  }
  // MemberID:string
  public function RemoveMember($MemberID){
    if (!$this->GetOnlineState()) return null;
    $args=array('MemberID'=>$MemberID);
    return self::Call('GroupManagement','RemoveMember',$args,null);
  }
  // ObjectID:string, UpdateID:ui4, Instance:ui4
  public function RemoveTrackFromQueue($ObjectID,$UpdateID,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'ObjectID'=>$ObjectID,'UpdateID'=>$UpdateID);
    return self::Call('AVTransport','RemoveTrackFromQueue',$args,null);
  }
  // QueueID:ui4, UpdateID:ui4, StartingIndex:ui4, NumberOfTracks:ui4
  public function RemoveTrackRange($QueueID,$UpdateID,$StartingIndex,$NumberOfTracks){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'UpdateID'=>$UpdateID,'StartingIndex'=>$StartingIndex,'NumberOfTracks'=>$NumberOfTracks);
    $filter=array('NewUpdateID');
    return self::Call('Queue','RemoveTrackRange',$args,$filter);
  }
  // UpdateID:ui4, StartingIndex:ui4, NumberOfTracks:ui4, Instance:ui4
  public function RemoveTrackRangeFromQueue($UpdateID,$StartingIndex,$NumberOfTracks,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'UpdateID'=>$UpdateID,'StartingIndex'=>$StartingIndex,'NumberOfTracks'=>$NumberOfTracks);
    $filter=array('NewUpdateID');
    return self::Call('AVTransport','RemoveTrackRangeFromQueue',$args,$filter);
  }
  // QueueID:ui4, StartingIndex:ui4, NumberOfTracks:ui4, InsertBefore:ui4, UpdateID:ui4
  public function ReorderTracks($QueueID,$StartingIndex,$NumberOfTracks,$InsertBefore,$UpdateID){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'StartingIndex'=>$StartingIndex,'NumberOfTracks'=>$NumberOfTracks,'InsertBefore'=>$InsertBefore,'UpdateID'=>$UpdateID);
    $filter=array('NewUpdateID');
    return self::Call('Queue','ReorderTracks',$args,$filter);
  }
  // StartingIndex:ui4, NumberOfTracks:ui4, InsertBefore:ui4, UpdateID:ui4, Instance:ui4
  public function ReorderTracksInQueue($StartingIndex,$NumberOfTracks,$InsertBefore,$UpdateID,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'StartingIndex'=>$StartingIndex,'NumberOfTracks'=>$NumberOfTracks,'InsertBefore'=>$InsertBefore,'UpdateID'=>$UpdateID);
    return self::Call('AVTransport','ReorderTracksInQueue',$args,null);
  }
  // ObjectID:string, UpdateID:ui4, TrackList:string, NewPositionList:string, Instance:ui4
  public function ReorderTracksInSavedQueue($ObjectID,$UpdateID,$TrackList,$NewPositionList,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'ObjectID'=>$ObjectID,'UpdateID'=>$UpdateID,'TrackList'=>$TrackList,'NewPositionList'=>$NewPositionList);
    $filter=array('QueueLengthChange','NewQueueLength','NewUpdateID');
    return self::Call('AVTransport','ReorderTracksInSavedQueue',$args,$filter);
  }
  // AccountUDN:string, NewAccountID:string, NewAccountPassword:string, AccountToken:string, AccountKey:string, OAuthDeviceID:string
  public function ReplaceAccountX($AccountUDN,$NewAccountID,$NewAccountPassword,$AccountToken,$AccountKey,$OAuthDeviceID){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountUDN'=>$AccountUDN,'NewAccountID'=>$NewAccountID,'NewAccountPassword'=>$NewAccountPassword,'AccountToken'=>$AccountToken,'AccountKey'=>$AccountKey,'OAuthDeviceID'=>$OAuthDeviceID);
    $filter=array('NewAccountUDN');
    return self::Call('SystemProperties','ReplaceAccountX',$args,$filter);
  }
  // QueueID:ui4, UpdateID:ui4, ContainerURI:string, ContainerMetaData:string, CurrentTrackIndex:ui4, NewCurrentTrackIndices:string, NumberOfURIs:ui4, EnqueuedURIsAndMetaData:string
  public function ReplaceAllTracks($QueueID,$UpdateID,$ContainerURI,$ContainerMetaData,$CurrentTrackIndex,$NewCurrentTrackIndices,$NumberOfURIs,$EnqueuedURIsAndMetaData){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'UpdateID'=>$UpdateID,'ContainerURI'=>$ContainerURI,'ContainerMetaData'=>$ContainerMetaData,'CurrentTrackIndex'=>$CurrentTrackIndex,'NewCurrentTrackIndices'=>$NewCurrentTrackIndices,'NumberOfURIs'=>$NumberOfURIs,'EnqueuedURIsAndMetaData'=>$EnqueuedURIsAndMetaData);
    $filter=array('NewQueueLength','NewUpdateID');
    return self::Call('Queue','ReplaceAllTracks',$args,$filter);
  }

  public function ReportAlarmStartedRunning(){
    if (!$this->GetOnlineState()) return null;
    return self::Call('ZoneGroupTopology','ReportAlarmStartedRunning',null,null);
  }
  // MemberID:string, ResultCode:i4
  public function ReportTrackBufferingResult($MemberID,$ResultCode){
    if (!$this->GetOnlineState()) return null;
    $args=array('MemberID'=>$MemberID,'ResultCode'=>$ResultCode);
    return self::Call('GroupManagement','ReportTrackBufferingResult',$args,null);
  }
  // DeviceUUID:string, DesiredAction:string
  public function ReportUnresponsiveDevice($DeviceUUID,$DesiredAction){
    if (!$this->GetOnlineState()) return null;
    $args=array('DeviceUUID'=>$DeviceUUID,'DesiredAction'=>$DesiredAction);
    return self::Call('ZoneGroupTopology','ReportUnresponsiveDevice',$args,null);
  }
  // SortOrder:string
  public function RequestResort($SortOrder){
    if (!$this->GetOnlineState()) return null;
    $args=array('SortOrder'=>$SortOrder);
    return self::Call('ContentDirectory','RequestResort',$args,null);
  }
  // Instance:ui4
  public function ResetBasicEQ($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    $filter=array('Bass','Treble','Loudness','LeftVolume','RightVolume');
    return self::Call('RenderingControl','ResetBasicEQ',$args,$filter);
  }
  // EQType:string, Instance:ui4
  public function ResetExtEQ($EQType,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'EQType'=>$EQType);
    return self::Call('RenderingControl','ResetExtEQ',$args,null);
  }

  public function ResetThirdPartyCredentials(){
    if (!$this->GetOnlineState()) return null;
    return self::Call('SystemProperties','ResetThirdPartyCredentials',null,null);
  }
  // Instance:ui4, Channel:string
  public function RestoreVolumePriorToRamp($Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel);
    return self::Call('RenderingControl','RestoreVolumePriorToRamp',$args,null);
  }
  // AlarmID:ui4, LoggedStartTime:string, Duration:string, ProgramURI:string, ProgramMetaData:string, PlayMode:string, Volume:ui2, IncludeLinkedZones:boolean, Instance:ui4
  public function RunAlarm($AlarmID,$LoggedStartTime,$Duration,$ProgramURI,$ProgramMetaData,$PlayMode,$Volume,$IncludeLinkedZones,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'AlarmID'=>$AlarmID,'LoggedStartTime'=>$LoggedStartTime,'Duration'=>$Duration,'ProgramURI'=>$ProgramURI,'ProgramMetaData'=>$ProgramMetaData,'PlayMode'=>$PlayMode,'Volume'=>$Volume,'IncludeLinkedZones'=>$IncludeLinkedZones);
    return self::Call('AVTransport','RunAlarm',$args,null);
  }
  // QueueID:ui4, Title:string, ObjectID:string
  public function SaveAsSonosPlaylist($QueueID,$Title,$ObjectID){
    if (!$this->GetOnlineState()) return null;
    $args=array('QueueID'=>$QueueID,'Title'=>$Title,'ObjectID'=>$ObjectID);
    $filter=array('AssignedObjectID');
    return self::Call('Queue','SaveAsSonosPlaylist',$args,$filter);
  }
  // Title:string, ObjectID:string, Instance:ui4
  public function SaveQueue($Title,$ObjectID,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Title'=>$Title,'ObjectID'=>$ObjectID);
    $filter=array('AssignedObjectID');
    return self::Call('AVTransport','SaveQueue',$args,$filter);
  }
  // Target:string, Instance:ui4, Unit:string
  public function Seek($Target,$Instance=0,$Unit='TRACK_NR'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Unit'=>$Unit,'Target'=>$Target);
    return self::Call('AVTransport','Seek',$args,null);
  }
  // ChannelMapSet:string
  public function SeparateStereoPair($ChannelMapSet){
    if (!$this->GetOnlineState()) return null;
    $args=array('ChannelMapSet'=>$ChannelMapSet);
    return self::Call('DeviceProperties','SeparateStereoPair',$args,null);
  }
  // CurrentURI:string, CurrentURIMetaData:string, Instance:ui4
  public function SetAVTransportURI($CurrentURI,$CurrentURIMetaData,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CurrentURI'=>$CurrentURI,'CurrentURIMetaData'=>$CurrentURIMetaData);
    return self::Call('AVTransport','SetAVTransportURI',$args,null);
  }
  // AccountUDN:string, AccountNickname:string
  public function SetAccountNicknameX($AccountUDN,$AccountNickname){
    if (!$this->GetOnlineState()) return null;
    $args=array('AccountUDN'=>$AccountUDN,'AccountNickname'=>$AccountNickname);
    return self::Call('SystemProperties','SetAccountNicknameX',$args,null);
  }
  // IncludeLinkedZones:boolean
  public function SetAutoplayLinkedZones($IncludeLinkedZones){
    if (!$this->GetOnlineState()) return null;
    $args=array('IncludeLinkedZones'=>$IncludeLinkedZones);
    return self::Call('DeviceProperties','SetAutoplayLinkedZones',$args,null);
  }
  // RoomUUID:string
  public function SetAutoplayRoomUUID($RoomUUID){
    if (!$this->GetOnlineState()) return null;
    $args=array('RoomUUID'=>$RoomUUID);
    return self::Call('DeviceProperties','SetAutoplayRoomUUID',$args,null);
  }
  // Volume:ui2
  public function SetAutoplayVolume($Volume){
    if (!$this->GetOnlineState()) return null;
    $args=array('Volume'=>$Volume);
    return self::Call('DeviceProperties','SetAutoplayVolume',$args,null);
  }
  // DesiredBass:i2, Instance:ui4
  public function SetBass($DesiredBass,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredBass'=>$DesiredBass);
    return self::Call('RenderingControl','SetBass',$args,null);
  }
  // Browseable:boolean
  public function SetBrowseable($Browseable){
    if (!$this->GetOnlineState()) return null;
    $args=array('Browseable'=>$Browseable);
    return self::Call('ContentDirectory','SetBrowseable',$args,null);
  }
  // ChannelMap:string, Instance:ui4
  public function SetChannelMap($ChannelMap,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'ChannelMap'=>$ChannelMap);
    return self::Call('RenderingControl','SetChannelMap',$args,null);
  }
  // CrossfadeMode:boolean, Instance:ui4
  public function SetCrossfadeMode($CrossfadeMode,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CrossfadeMode'=>$CrossfadeMode);
    return self::Call('AVTransport','SetCrossfadeMode',$args,null);
  }
  // DesiredDailyIndexRefreshTime:string
  public function SetDailyIndexRefreshTime($DesiredDailyIndexRefreshTime){
    if (!$this->GetOnlineState()) return null;
    $args=array('DesiredDailyIndexRefreshTime'=>$DesiredDailyIndexRefreshTime);
    return self::Call('AlarmClock','SetDailyIndexRefreshTime',$args,null);
  }
  // EQType:string, DesiredValue:i2, Instance:ui4
  public function SetEQ($EQType,$DesiredValue,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'EQType'=>$EQType,'DesiredValue'=>$DesiredValue);
    return self::Call('RenderingControl','SetEQ',$args,null);
  }
  // DesiredTimeFormat:string, DesiredDateFormat:string
  public function SetFormat($DesiredTimeFormat,$DesiredDateFormat){
    if (!$this->GetOnlineState()) return null;
    $args=array('DesiredTimeFormat'=>$DesiredTimeFormat,'DesiredDateFormat'=>$DesiredDateFormat);
    return self::Call('AlarmClock','SetFormat',$args,null);
  }
  // DesiredMute:boolean, Instance:ui4
  public function SetGroupMute($DesiredMute,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredMute'=>$DesiredMute);
    return self::Call('GroupRenderingControl','SetGroupMute',$args,null);
  }
  // DesiredVolume:ui2, Instance:ui4
  public function SetGroupVolume($DesiredVolume,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredVolume'=>$DesiredVolume);
    return self::Call('GroupRenderingControl','SetGroupVolume',$args,null);
  }
  // DesiredLEDState:string
  public function SetLEDState($DesiredLEDState){
    if (!$this->GetOnlineState()) return null;
    $args=array('DesiredLEDState'=>$DesiredLEDState);
    return self::Call('DeviceProperties','SetLEDState',$args,null);
  }
  // DesiredLoudness:boolean, Instance:ui4, Channel:string
  public function SetLoudness($DesiredLoudness,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'DesiredLoudness'=>$DesiredLoudness);
    return self::Call('RenderingControl','SetLoudness',$args,null);
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
  // DesiredFixed:boolean, Instance:ui4
  public function SetOutputFixed($DesiredFixed,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredFixed'=>$DesiredFixed);
    return self::Call('RenderingControl','SetOutputFixed',$args,null);
  }
  // NewPlayMode:string, Instance:ui4
  public function SetPlayMode($NewPlayMode,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'NewPlayMode'=>$NewPlayMode);
    return self::Call('AVTransport','SetPlayMode',$args,null);
  }
  // Adjustment:i4, Instance:ui4
  public function SetRelativeGroupVolume($Adjustment,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Adjustment'=>$Adjustment);
    $filter=array('NewVolume');
    return self::Call('GroupRenderingControl','SetRelativeGroupVolume',$args,$filter);
  }
  // Adjustment:i4, Instance:ui4, Channel:string
  public function SetRelativeVolume($Adjustment,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'Adjustment'=>$Adjustment);
    $filter=array('NewVolume');
    return self::Call('RenderingControl','SetRelativeVolume',$args,$filter);
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
  // CalibrationID:string, Coefficients:string, Instance:ui4
  public function SetSonarCalibrationX($CalibrationID,$Coefficients,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'CalibrationID'=>$CalibrationID,'Coefficients'=>$Coefficients);
    return self::Call('RenderingControl','SetSonarCalibrationX',$args,null);
  }
  // SonarEnabled:boolean, Instance:ui4
  public function SetSonarStatus($SonarEnabled,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'SonarEnabled'=>$SonarEnabled);
    return self::Call('RenderingControl','SetSonarStatus',$args,null);
  }
  // NewState:ui2, Instance:ui4
  public function SetState($NewState,$Instance=0) {
    switch($NewState){
      case RPC2SONOS_STATE_STOP : $s=$this->Stop($Instance)?RPC2SONOS_STATE_STOP:RPC2SONOS_STATE_ERROR; break;
      case RPC2SONOS_STATE_PREV : $s=$this->Previous($Instance)?RPC2SONOS_STATE_PLAY:RPC2SONOS_STATE_STOP;break;
      case RPC2SONOS_STATE_PLAY : $s=$this->Play($Instance)?RPC2SONOS_STATE_PLAY:RPC2SONOS_STATE_STOP; break;
      case RPC2SONOS_STATE_PAUSE: $s=$this->Pause($Instance)?RPC2SONOS_STATE_PAUSE:RPC2SONOS_STATE_STOP;break;
      case RPC2SONOS_STATE_NEXT : $s=$this->Next($Instance)?RPC2SONOS_STATE_PLAY:RPC2SONOS_STATE_STOP; break;
      default : return RPC2SONOS_STATE_ERROR;
    }
    return $s;
  }
  // VariableName:string, StringValue:string
  public function SetString($VariableName,$StringValue){
    if (!$this->GetOnlineState()) return null;
    $args=array('VariableName'=>$VariableName,'StringValue'=>$StringValue);
    return self::Call('SystemProperties','SetString',$args,null);
  }
  // DesiredTime:string, TimeZoneForDesiredTime:string
  public function SetTimeNow($DesiredTime,$TimeZoneForDesiredTime){
    if (!$this->GetOnlineState()) return null;
    $args=array('DesiredTime'=>$DesiredTime,'TimeZoneForDesiredTime'=>$TimeZoneForDesiredTime);
    return self::Call('AlarmClock','SetTimeNow',$args,null);
  }
  // DesiredTimeServer:string
  public function SetTimeServer($DesiredTimeServer){
    if (!$this->GetOnlineState()) return null;
    $args=array('DesiredTimeServer'=>$DesiredTimeServer);
    return self::Call('AlarmClock','SetTimeServer',$args,null);
  }
  // Index:i4, AutoAdjustDst:boolean
  public function SetTimeZone($Index,$AutoAdjustDst){
    if (!$this->GetOnlineState()) return null;
    $args=array('Index'=>$Index,'AutoAdjustDst'=>$AutoAdjustDst);
    return self::Call('AlarmClock','SetTimeZone',$args,null);
  }
  // DesiredTreble:i2, Instance:ui4
  public function SetTreble($DesiredTreble,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'DesiredTreble'=>$DesiredTreble);
    return self::Call('RenderingControl','SetTreble',$args,null);
  }
  // UseVolume:boolean
  public function SetUseAutoplayVolume($UseVolume){
    if (!$this->GetOnlineState()) return null;
    $args=array('UseVolume'=>$UseVolume);
    return self::Call('DeviceProperties','SetUseAutoplayVolume',$args,null);
  }
  // DesiredVolume:ui2, Instance:ui4, Channel:string
  public function SetVolume($DesiredVolume,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'DesiredVolume'=>$DesiredVolume);
    return self::Call('RenderingControl','SetVolume',$args,null);
  }
  // DesiredVolume:i2, Instance:ui4, Channel:string
  public function SetVolumeDB($DesiredVolume,$Instance=0,$Channel='Master'){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Channel'=>$Channel,'DesiredVolume'=>$DesiredVolume);
    return self::Call('RenderingControl','SetVolumeDB',$args,null);
  }
  // DesiredZoneName:string, DesiredIcon:string, DesiredConfiguration:string
  public function SetZoneAttributes($DesiredZoneName,$DesiredIcon,$DesiredConfiguration){
    if (!$this->GetOnlineState()) return null;
    $args=array('DesiredZoneName'=>$DesiredZoneName,'DesiredIcon'=>$DesiredIcon,'DesiredConfiguration'=>$DesiredConfiguration);
    return self::Call('DeviceProperties','SetZoneAttributes',$args,null);
  }
  // Instance:ui4
  public function SnapshotGroupVolume($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('GroupRenderingControl','SnapshotGroupVolume',$args,null);
  }
  // Duration:string, Instance:ui4
  public function SnoozeAlarm($Duration,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'Duration'=>$Duration);
    return self::Call('AVTransport','SnoozeAlarm',$args,null);
  }
  // ProgramURI:string, ProgramMetaData:string, Volume:ui2, IncludeLinkedZones:boolean, ResetVolumeAfter:boolean, Instance:ui4
  public function StartAutoplay($ProgramURI,$ProgramMetaData,$Volume,$IncludeLinkedZones,$ResetVolumeAfter,$Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance,'ProgramURI'=>$ProgramURI,'ProgramMetaData'=>$ProgramMetaData,'Volume'=>$Volume,'IncludeLinkedZones'=>$IncludeLinkedZones,'ResetVolumeAfter'=>$ResetVolumeAfter);
    return self::Call('AVTransport','StartAutoplay',$args,null);
  }
  // Instance:ui4
  public function Stop($Instance=0){
    if (!$this->GetOnlineState()) return null;
    $args=array('InstanceID'=>$Instance);
    return self::Call('AVTransport','Stop',$args,null);
  }
  // IncludeControllers:boolean, Type:string
  public function SubmitDiagnostics($IncludeControllers,$Type){
    if (!$this->GetOnlineState()) return null;
    $args=array('IncludeControllers'=>$IncludeControllers,'Type'=>$Type);
    $filter=array('DiagnosticID');
    return self::Call('ZoneGroupTopology','SubmitDiagnostics',$args,$filter);
  }
  // ID:ui4, StartLocalTime:string, Duration:string, Recurrence:string, Enabled:boolean, RoomUUID:string, ProgramURI:string, ProgramMetaData:string, PlayMode:string, Volume:ui2, IncludeLinkedZones:boolean
  public function UpdateAlarm($ID,$StartLocalTime,$Duration,$Recurrence,$Enabled,$RoomUUID,$ProgramURI,$ProgramMetaData,$PlayMode,$Volume,$IncludeLinkedZones){
    if (!$this->GetOnlineState()) return null;
    $args=array('ID'=>$ID,'StartLocalTime'=>$StartLocalTime,'Duration'=>$Duration,'Recurrence'=>$Recurrence,'Enabled'=>$Enabled,'RoomUUID'=>$RoomUUID,'ProgramURI'=>$ProgramURI,'ProgramMetaData'=>$ProgramMetaData,'PlayMode'=>$PlayMode,'Volume'=>$Volume,'IncludeLinkedZones'=>$IncludeLinkedZones);
    return self::Call('AlarmClock','UpdateAlarm',$args,null);
  }

  public function UpdateAvailableServices(){
    if (!$this->GetOnlineState()) return null;
    return self::Call('MusicServices','UpdateAvailableServices',null,null);
  }
  // ObjectID:string, CurrentTagValue:string, NewTagValue:string
  public function UpdateObject($ObjectID,$CurrentTagValue,$NewTagValue){
    if (!$this->GetOnlineState()) return null;
    $args=array('ObjectID'=>$ObjectID,'CurrentTagValue'=>$CurrentTagValue,'NewTagValue'=>$NewTagValue);
    return self::Call('ContentDirectory','UpdateObject',$args,null);
  }
  // Instance:ui4
  protected function UpdatePlayMode($Instance=0){
    static $modes=array(
        'NORMAL'=>array(false,false,false),
        'REPEAT_ALL'=>array(true,false,true),
        'REPEAT_ONE'=>array(true,false,false),
        'SHUFFLE_NOREPEAT'=>array(false,true,false),
        'SHUFFLE'=>array(false,true,false),
        'SHUFFLE_REPEAT_ONE'=>array(true,true,false),
    );
    if(empty($this->_PlayModes))
      foreach($modes as $k=>$a)$this->_PlayModes[$a[0]][$a[1]][$a[2]]=$k;
    if(!$t=$this->GetTransportSettings($Instance))return false;
    list($this->_boRepeat,$this->_boShuffle,$this->_boAll)=$modes[$t['PlayMode']];
  }
}
?>