unit LoginForm;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, IdBaseComponent, IdComponent, IdTCPConnection,
  IdTCPClient, IdHTTP, ExtCtrls, IdCookieManager, ComCtrls,uLkJSON,IniFiles,shellapi;

type
  TFormLogin = class(TForm)
    IdHTTP: TIdHTTP;
    IdCookieManager1: TIdCookieManager;
    StatusBar: TStatusBar;
    MemoLog: TMemo;
    PanelLogin: TPanel;
    Label1: TLabel;
    EditName: TEdit;
    Label2: TLabel;
    EditPwd: TEdit;
    ButtonOK: TButton;
    PanelLogined: TPanel;
    LabelLogined: TLabel;
    Button1: TButton;

    procedure ButtonOKClick(Sender: TObject);
    procedure FormCreate(Sender: TObject);
    procedure Button1Click(Sender: TObject);
  private
    { Private declarations }
  public
    { Public declarations }
    authCode:String;
    userName:String;
    uid:String;
    serverAddr:String;
    function GetMethod(URL: String; Max: Integer): String;
    function PostMethod(URL, Data: String; max: Integer): String;
    function DoHttpResponse(Response:String):  Integer;
    procedure ShowLogin(flag:Boolean);
    procedure ShowLogined(flag:Boolean;info:String);
    procedure getIni();
    procedure setAuthCode(code:String);
    procedure DoAutoLogin()  ;
    procedure showBrowser(phone:string);
  end;

var
  FormLogin: TFormLogin;

implementation

{$R *.dfm}

procedure TFormLogin.ButtonOKClick(Sender: TObject);
var
  uName,uPwd:String;
  RespData: TStringStream;
  tmpStr: String;
begin
  uName := editName.Text;
  uPwd := editPwd.Text;
  tmpStr := PostMethod(self.serverAddr+ '/client/login/','uName='+uName+'&uPwd='+uPwd,0);
  DoHttpResponse(tmpStr);

end;

function TFormLogin.GetMethod(URL: String; Max: Integer): String;
var
RespData: TStringStream;
begin
  RespData := TStringStream.Create('');
  try
    try
      IdHTTP.Get(URL, RespData);
      IdHTTP.Request.Referer := URL;
      Result := RespData.DataString;
      if (MemoLog <> nil) then
      begin
         MemoLog.Lines.Append(Result);
      end
    except
      Dec(Max);
      if Max = 0 then
      begin
        Result := '';
        Exit;
      end;
      Result := GetMethod(URL, Max);
    end;
  finally
    FreeAndNil(RespData);
  end;
end;

function TFormLogin.PostMethod(URL, Data: String; max: Integer): String;
var
PostData, RespData: TStringStream;
begin
RespData := TStringStream.Create('');
PostData := TStringStream.Create(Data);
try
    try
      if IdHTTP = nil then Exit;
      IdHTTP.Request.ContentType := 'application/x-www-form-urlencoded';
      IdHTTP.Post(URL, PostData, RespData);
      Result := RespData.DataString;
      IdHTTP.Request.Referer := URL;
      if (MemoLog <> nil) then
      begin
         MemoLog.Lines.Append(Result);
      end
    except
      Dec(Max);
      if Max <= 0 then
      begin
        Result := '';
        Exit;
      end;
      Result := PostMethod(URL, Data, Max);
    end;
finally
    IdHTTP.Disconnect;
    FreeAndNil(RespData);
    FreeAndNil(PostData);
end;
end;

function TFormLogin.DoHttpResponse(Response:String):  Integer;
    var
  js,data,err:TlkJSONobject;
  rst: integer;
  errMe: String;
begin
try
  js:=TlkJSON.ParseText(Response) as TlkJSONobject;
  rst := js.getInt('rstno');
  errMe:='';

  if (rst<0) then
  begin
    errMe:=((js.Field['data'] as TlkJSONobject).Field['err'] as TlkJSONobject).getString('msg');
    StatusBar.SimpleText := errMe;
  end
  else
  begin
    self.authCode:= (js.Field['data'] as TlkJSONobject).getString('authCode');
    self.userName:= (js.Field['data'] as TlkJSONobject).getString('name');
    self.uid:= (js.Field['data'] as TlkJSONobject).getString('uid');
    StatusBar.SimpleText :='登录成功，等待来电';
    setAuthCode(self.authCode);
    self.ShowLogin(false);
    self.ShowLogined(true,self.userName+' 您好！登录成功，等待来电');
  end;
  if (MemoLog <> nil) then
  begin
         MemoLog.Lines.Append(IntToStr(rst));
         MemoLog.Lines.Append(errMe);
         MemoLog.Lines.Append(self.authCode);
  end;
  js.Free;
  except
     StatusBar.SimpleText :='登录失败，请检查网络或联系客服';
  end;
end;

procedure TFormLogin.getIni();
var
  ini:tinifile;
begin
  ini:=tinifile.Create(extractfilepath(application.ExeName)+'wanjia.ini');
  self.authCode :=ini.ReadString('User','authCode','error');
  self.serverAddr:=ini.ReadString('Sys','server','err');
  ini.Free;
end;
procedure TFormLogin.setAuthCode(code:String);
var
  ini:tinifile;
begin
  ini:=tinifile.Create(extractfilepath(application.ExeName)+'wanjia.ini');
  ini.writeString('User','authCode',code);
  ini.Free;
end;
procedure TFormLogin.FormCreate(Sender: TObject);

begin
  self.getIni();
  if (self.authCode='error') or (self.authCode='') then
  begin
    self.ShowLogin(true);
  end
  else
  begin
    self.ShowLogin(false);
    self.ShowLogined(true,'自动登录中...请稍候');
    self.DoAutoLogin();
  end;
end;

procedure TFormLogin.ShowLogin(flag:Boolean);
begin
    PanelLogin.Visible :=flag;
end;

procedure TFormLogin.ShowLogined(flag:Boolean;info:String);
begin
  PanelLogined.Visible :=flag;
  self.LabelLogined.Caption:=info;
end;

procedure TFormLogin.DoAutoLogin();
var
  tmpStr:String;
begin
  tmpStr := GetMethod(self.serverAddr+'/client/checkAuth/'+self.authCode,0);
  DoHTTPResponse(tmpStr);
end;

procedure TFormLogin.showBrowser(phone:string);
begin
  ShellExecute(handle,nil,pchar(self.serverAddr+ '/client/call/'+self.authCode+'/'+phone),nil,nil,SW_shownormal);
end;

procedure TFormLogin.Button1Click(Sender: TObject);
begin
  showBrowser('15800972778');
end;

end.
