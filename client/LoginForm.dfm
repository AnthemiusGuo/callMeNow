object FormLogin: TFormLogin
  Left = 192
  Top = 107
  AutoScroll = False
  AutoSize = True
  BorderIcons = [biSystemMenu, biMinimize]
  Caption = #30331#24405#31995#32479
  ClientHeight = 288
  ClientWidth = 354
  Color = clBtnFace
  Font.Charset = DEFAULT_CHARSET
  Font.Color = clWindowText
  Font.Height = -11
  Font.Name = 'MS Sans Serif'
  Font.Style = []
  OldCreateOrder = False
  OnCreate = FormCreate
  PixelsPerInch = 96
  TextHeight = 13
  object StatusBar: TStatusBar
    Left = 0
    Top = 266
    Width = 354
    Height = 22
    Panels = <>
    SimplePanel = True
  end
  object MemoLog: TMemo
    Left = 8
    Top = 128
    Width = 337
    Height = 129
    TabOrder = 1
  end
  object PanelLogin: TPanel
    Left = 8
    Top = 0
    Width = 337
    Height = 129
    BevelOuter = bvNone
    TabOrder = 2
    Visible = False
    object Label1: TLabel
      Left = 17
      Top = 17
      Width = 80
      Height = 16
      Caption = #29992#25143#25163#26426#21495
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -16
      Font.Name = #40657#20307
      Font.Style = []
      ParentFont = False
    end
    object Label2: TLabel
      Left = 65
      Top = 50
      Width = 32
      Height = 16
      Caption = #23494#30721
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -16
      Font.Name = #40657#20307
      Font.Style = []
      ParentFont = False
    end
    object EditName: TEdit
      Left = 113
      Top = 9
      Width = 217
      Height = 24
      BevelEdges = []
      BevelInner = bvNone
      BevelOuter = bvNone
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -16
      Font.Name = #40657#20307
      Font.Style = []
      ParentFont = False
      TabOrder = 0
    end
    object EditPwd: TEdit
      Left = 113
      Top = 50
      Width = 217
      Height = 24
      BevelInner = bvNone
      BevelOuter = bvNone
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -16
      Font.Name = #40657#20307
      Font.Style = []
      ParentFont = False
      PasswordChar = '*'
      TabOrder = 1
    end
    object ButtonOK: TButton
      Left = 255
      Top = 97
      Width = 75
      Height = 25
      Caption = #30830#23450
      Font.Charset = DEFAULT_CHARSET
      Font.Color = clWindowText
      Font.Height = -16
      Font.Name = #40657#20307
      Font.Style = []
      ParentFont = False
      TabOrder = 2
      OnClick = ButtonOKClick
    end
  end
  object PanelLogined: TPanel
    Left = 8
    Top = 0
    Width = 337
    Height = 129
    TabOrder = 3
    Visible = False
    object LabelLogined: TLabel
      Left = 16
      Top = 48
      Width = 305
      Height = 13
      Alignment = taCenter
      AutoSize = False
    end
    object Button1: TButton
      Left = 232
      Top = 80
      Width = 75
      Height = 25
      Caption = 'Button1'
      TabOrder = 0
      OnClick = Button1Click
    end
  end
  object IdHTTP: TIdHTTP
    MaxLineAction = maException
    ReadTimeout = 0
    AllowCookies = True
    ProxyParams.BasicAuthentication = False
    ProxyParams.ProxyPort = 0
    Request.ContentLength = -1
    Request.ContentRangeEnd = 0
    Request.ContentRangeStart = 0
    Request.ContentType = 'text/html'
    Request.Accept = 'text/html, */*'
    Request.BasicAuthentication = False
    Request.UserAgent = 'Mozilla/3.0 (compatible; Indy Library)'
    HTTPOptions = [hoForceEncodeParams]
    CookieManager = IdCookieManager1
    Left = 16
    Top = 96
  end
  object IdCookieManager1: TIdCookieManager
    Left = 56
    Top = 96
  end
end
