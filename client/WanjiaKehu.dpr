program WanjiaKehu;

uses
  Forms,
  LoginForm in 'LoginForm.pas' {FormLogin},
  uLkJSON in 'third\uLkJSON.pas';

{$R *.res}

begin
  Application.Initialize;
  Application.Title := '��ҿͻ� �绰�ͻ�����ϵͳ';
  Application.CreateForm(TFormLogin, FormLogin);
  Application.Run;
end.
