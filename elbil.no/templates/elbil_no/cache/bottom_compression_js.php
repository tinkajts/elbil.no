<?php header("Content-type: text/javascript"); ?>
var config=new Object();var tt_Debug=true
var tt_Enabled=true
var TagsToTip=true
config.Above=false
config.BgColor='#E4E7FF'
config.BgImg=''
config.BorderColor='#002299'
config.BorderStyle='solid'
config.BorderWidth=1
config.CenterMouse=false
config.ClickClose=false
config.CloseBtn=false
config.CloseBtnColors=['#990000','#FFFFFF','#DD3333','#FFFFFF']
config.CloseBtnText='&nbsp;X&nbsp;'
config.CopyContent=true
config.Delay=400
config.Duration=0
config.FadeIn=0
config.FadeOut=0
config.FadeInterval=30
config.Fix=null
config.FollowMouse=true
config.FontColor='#000044'
config.FontFace='Verdana,Geneva,sans-serif'
config.FontSize='8pt'
config.FontWeight='normal'
config.Left=false
config.OffsetX=14
config.OffsetY=8
config.Opacity=100
config.Padding=3
config.Shadow=false
config.ShadowColor='#C0C0C0'
config.ShadowWidth=5
config.Sticky=false
config.TextAlign='left'
config.Title=''
config.TitleAlign='left'
config.TitleBgColor=''
config.TitleFontColor='#ffffff'
config.TitleFontFace=''
config.TitleFontSize=''
config.Width=0
function Tip()
{tt_Tip(arguments,null);}
function TagToTip()
{if(TagsToTip)
{var t2t=tt_GetElt(arguments[0]);if(t2t)
tt_Tip(arguments,t2t);}}
var tt_aElt=new Array(10),tt_aV=new Array(),tt_sContent,tt_scrlX=0,tt_scrlY=0,tt_musX,tt_musY,tt_over,tt_x,tt_y,tt_w,tt_h;function tt_Extension()
{tt_ExtCmdEnum();tt_aExt[tt_aExt.length]=this;return this;}
function tt_SetTipPos(x,y)
{var css=tt_aElt[0].style;tt_x=x;tt_y=y;css.left=x+"px";css.top=y+"px";if(tt_ie56)
{var ifrm=tt_aElt[tt_aElt.length-1];if(ifrm)
{ifrm.style.left=css.left;ifrm.style.top=css.top;}}}
function tt_Hide()
{if(tt_db&&tt_iState)
{if(tt_iState&0x2)
{tt_aElt[0].style.visibility="hidden";tt_ExtCallFncs(0,"Hide");}
tt_tShow.EndTimer();tt_tHide.EndTimer();tt_tDurt.EndTimer();tt_tFade.EndTimer();if(!tt_op&&!tt_ie)
{tt_tWaitMov.EndTimer();tt_bWait=false;}
if(tt_aV[CLICKCLOSE])
tt_RemEvtFnc(document,"mouseup",tt_HideInit);tt_AddRemOutFnc(false);tt_ExtCallFncs(0,"Kill");if(tt_t2t&&!tt_aV[COPYCONTENT])
{tt_t2t.style.display="none";tt_MovDomNode(tt_t2t,tt_aElt[6],tt_t2tDad);}
tt_iState=0;tt_over=null;tt_ResetMainDiv();if(tt_aElt[tt_aElt.length-1])
tt_aElt[tt_aElt.length-1].style.display="none";}}
function tt_GetElt(id)
{return(document.getElementById?document.getElementById(id):document.all?document.all[id]:null);}
function tt_GetDivW(el)
{return(el?(el.offsetWidth||el.style.pixelWidth||0):0);}
function tt_GetDivH(el)
{return(el?(el.offsetHeight||el.style.pixelHeight||0):0);}
function tt_GetScrollX()
{return(window.pageXOffset||(tt_db?(tt_db.scrollLeft||0):0));}
function tt_GetScrollY()
{return(window.pageYOffset||(tt_db?(tt_db.scrollTop||0):0));}
function tt_GetClientW()
{return(document.body&&(typeof(document.body.clientWidth)!=tt_u)?document.body.clientWidth:(typeof(window.innerWidth)!=tt_u)?window.innerWidth:tt_db?(tt_db.clientWidth||0):0);}
function tt_GetClientH()
{return(document.body&&(typeof(document.body.clientHeight)!=tt_u)?document.body.clientHeight:(typeof(window.innerHeight)!=tt_u)?window.innerHeight:tt_db?(tt_db.clientHeight||0):0);}
function tt_GetEvtX(e)
{return(e?((typeof(e.pageX)!=tt_u)?e.pageX:(e.clientX+tt_scrlX)):0);}
function tt_GetEvtY(e)
{return(e?((typeof(e.pageY)!=tt_u)?e.pageY:(e.clientY+tt_scrlY)):0);}
function tt_AddEvtFnc(el,sEvt,PFnc)
{if(el)
{if(el.addEventListener)
el.addEventListener(sEvt,PFnc,false);else
el.attachEvent("on"+sEvt,PFnc);}}
function tt_RemEvtFnc(el,sEvt,PFnc)
{if(el)
{if(el.removeEventListener)
el.removeEventListener(sEvt,PFnc,false);else
el.detachEvent("on"+sEvt,PFnc);}}
var tt_aExt=new Array(),tt_db,tt_op,tt_ie,tt_ie56,tt_bBoxOld,tt_body,tt_flagOpa,tt_maxPosX,tt_maxPosY,tt_iState=0,tt_opa,tt_bJmpVert,tt_t2t,tt_t2tDad,tt_elDeHref,tt_tShow=new Number(0),tt_tHide=new Number(0),tt_tDurt=new Number(0),tt_tFade=new Number(0),tt_tWaitMov=new Number(0),tt_bWait=false,tt_u="undefined";function tt_Init()
{tt_MkCmdEnum();if(!tt_Browser()||!tt_MkMainDiv())
return;tt_IsW3cBox();tt_OpaSupport();tt_AddEvtFnc(document,"mousemove",tt_Move);if(TagsToTip||tt_Debug)
tt_SetOnloadFnc();tt_AddEvtFnc(window,"scroll",function()
{tt_scrlX=tt_GetScrollX();tt_scrlY=tt_GetScrollY();if(tt_iState&&!(tt_aV[STICKY]&&(tt_iState&2)))
tt_HideInit();});tt_AddEvtFnc(window,"unload",tt_Hide);tt_Hide();}
function tt_MkCmdEnum()
{var n=0;for(var i in config)
eval("window."+i.toString().toUpperCase()+" = "+n++);tt_aV.length=n;}
function tt_Browser()
{var n,nv,n6,w3c;n=navigator.userAgent.toLowerCase(),nv=navigator.appVersion;tt_op=(document.defaultView&&typeof(eval("w"+"indow"+"."+"o"+"p"+"er"+"a"))!=tt_u);tt_ie=n.indexOf("msie")!=-1&&document.all&&!tt_op;if(tt_ie)
{var ieOld=(!document.compatMode||document.compatMode=="BackCompat");tt_db=!ieOld?document.documentElement:(document.body||null);if(tt_db)
tt_ie56=parseFloat(nv.substring(nv.indexOf("MSIE")+5))>=5.5&&typeof document.body.style.maxHeight==tt_u;}
else
{tt_db=document.documentElement||document.body||(document.getElementsByTagName?document.getElementsByTagName("body")[0]:null);if(!tt_op)
{n6=document.defaultView&&typeof document.defaultView.getComputedStyle!=tt_u;w3c=!n6&&document.getElementById;}}
tt_body=(document.getElementsByTagName?document.getElementsByTagName("body")[0]:(document.body||null));if(tt_ie||n6||tt_op||w3c)
{if(tt_body&&tt_db)
{if(document.attachEvent||document.addEventListener)
return true;}
else
tt_Err("wz_tooltip.js must be included INSIDE the body section,"
+" immediately after the opening <body> tag.");}
tt_db=null;return false;}
function tt_MkMainDiv()
{if(tt_body.insertAdjacentHTML)
tt_body.insertAdjacentHTML("afterBegin",tt_MkMainDivHtm());else if(typeof tt_body.innerHTML!=tt_u&&document.createElement&&tt_body.appendChild)
tt_body.appendChild(tt_MkMainDivDom());if(window.tt_GetMainDivRefs&&tt_GetMainDivRefs())
return true;tt_db=null;return false;}
function tt_MkMainDivHtm()
{return('<div id="WzTtDiV"></div>'+
(tt_ie56?('<iframe id="WzTtIfRm" src="javascript:false" scrolling="no" frameborder="0" style="filter:Alpha(opacity=0);position:absolute;top:0px;left:0px;display:none;"></iframe>'):''));}
function tt_MkMainDivDom()
{var el=document.createElement("div");if(el)
el.id="WzTtDiV";return el;}
function tt_GetMainDivRefs()
{tt_aElt[0]=tt_GetElt("WzTtDiV");if(tt_ie56&&tt_aElt[0])
{tt_aElt[tt_aElt.length-1]=tt_GetElt("WzTtIfRm");if(!tt_aElt[tt_aElt.length-1])
tt_aElt[0]=null;}
if(tt_aElt[0])
{var css=tt_aElt[0].style;css.visibility="hidden";css.position="absolute";css.overflow="hidden";return true;}
return false;}
function tt_ResetMainDiv()
{var w=(window.screen&&screen.width)?screen.width:10000;tt_SetTipPos(-w,0);tt_aElt[0].innerHTML="";tt_aElt[0].style.width=(w-1)+"px";}
function tt_IsW3cBox()
{var css=tt_aElt[0].style;css.padding="10px";css.width="40px";tt_bBoxOld=(tt_GetDivW(tt_aElt[0])==40);css.padding="0px";tt_ResetMainDiv();}
function tt_OpaSupport()
{var css=tt_body.style;tt_flagOpa=(typeof(css.filter)!=tt_u)?1:(typeof(css.KhtmlOpacity)!=tt_u)?2:(typeof(css.KHTMLOpacity)!=tt_u)?3:(typeof(css.MozOpacity)!=tt_u)?4:(typeof(css.opacity)!=tt_u)?5:0;}
function tt_SetOnloadFnc()
{tt_AddEvtFnc(document,"DOMContentLoaded",tt_HideSrcTags);tt_AddEvtFnc(window,"load",tt_HideSrcTags);if(tt_body.attachEvent)
tt_body.attachEvent("onreadystatechange",function(){if(tt_body.readyState=="complete")
tt_HideSrcTags();});if(/WebKit|KHTML/i.test(navigator.userAgent))
{var t=setInterval(function(){if(/loaded|complete/.test(document.readyState))
{clearInterval(t);tt_HideSrcTags();}},10);}}
function tt_HideSrcTags()
{if(!window.tt_HideSrcTags||window.tt_HideSrcTags.done)
return;window.tt_HideSrcTags.done=true;if(!tt_HideSrcTagsRecurs(tt_body))
tt_Err("To enable the capability to convert HTML elements to tooltips,"
+" you must set TagsToTip in the global tooltip configuration"
+" to true.");}
function tt_HideSrcTagsRecurs(dad)
{var a,ovr,asT2t;a=dad.childNodes||dad.children||null;for(var i=a?a.length:0;i;)
{--i;if(!tt_HideSrcTagsRecurs(a[i]))
return false;ovr=a[i].getAttribute?a[i].getAttribute("onmouseover"):(typeof a[i].onmouseover=="function")?a[i].onmouseover:null;if(ovr)
{asT2t=ovr.toString().match(/TagToTip\s*\(\s*'[^'.]+'\s*[\),]/);if(asT2t&&asT2t.length)
{if(!tt_HideSrcTag(asT2t[0]))
return false;}}}
return true;}
function tt_HideSrcTag(sT2t)
{var id,el;id=sT2t.replace(/.+'([^'.]+)'.+/,"$1");el=tt_GetElt(id);if(el)
{if(tt_Debug&&!TagsToTip)
return false;else
el.style.display="none";}
else
tt_Err("Invalid ID\n'"+id+"'\npassed to TagToTip()."
+" There exists no HTML element with that ID.");return true;}
function tt_Tip(arg,t2t)
{if(!tt_db)
return;if(tt_iState)
tt_Hide();if(!tt_Enabled)
return;tt_t2t=t2t;if(!tt_ReadCmds(arg))
return;tt_iState=0x1|0x4;tt_AdaptConfig1();tt_MkTipContent(arg);tt_MkTipSubDivs();tt_FormatTip();tt_bJmpVert=false;tt_maxPosX=tt_GetClientW()+tt_scrlX-tt_w-1;tt_maxPosY=tt_GetClientH()+tt_scrlY-tt_h-1;tt_AdaptConfig2();tt_Move();tt_ShowInit();}
function tt_ReadCmds(a)
{var i;i=0;for(var j in config)
tt_aV[i++]=config[j];if(a.length&1)
{for(i=a.length-1;i>0;i-=2)
tt_aV[a[i-1]]=a[i];return true;}
tt_Err("Incorrect call of Tip() or TagToTip().\n"
+"Each command must be followed by a value.");return false;}
function tt_AdaptConfig1()
{tt_ExtCallFncs(0,"LoadConfig");if(!tt_aV[TITLEBGCOLOR].length)
tt_aV[TITLEBGCOLOR]=tt_aV[BORDERCOLOR];if(!tt_aV[TITLEFONTCOLOR].length)
tt_aV[TITLEFONTCOLOR]=tt_aV[BGCOLOR];if(!tt_aV[TITLEFONTFACE].length)
tt_aV[TITLEFONTFACE]=tt_aV[FONTFACE];if(!tt_aV[TITLEFONTSIZE].length)
tt_aV[TITLEFONTSIZE]=tt_aV[FONTSIZE];if(tt_aV[CLOSEBTN])
{if(!tt_aV[CLOSEBTNCOLORS])
tt_aV[CLOSEBTNCOLORS]=new Array("","","","");for(var i=4;i;)
{--i;if(!tt_aV[CLOSEBTNCOLORS][i].length)
tt_aV[CLOSEBTNCOLORS][i]=(i&1)?tt_aV[TITLEFONTCOLOR]:tt_aV[TITLEBGCOLOR];}
if(!tt_aV[TITLE].length)
tt_aV[TITLE]=" ";}
if(tt_aV[OPACITY]==100&&typeof tt_aElt[0].style.MozOpacity!=tt_u&&!Array.every)
tt_aV[OPACITY]=99;if(tt_aV[FADEIN]&&tt_flagOpa&&tt_aV[DELAY]>100)
tt_aV[DELAY]=Math.max(tt_aV[DELAY]-tt_aV[FADEIN],100);}
function tt_AdaptConfig2()
{if(tt_aV[CENTERMOUSE])
tt_aV[OFFSETX]-=((tt_w-(tt_aV[SHADOW]?tt_aV[SHADOWWIDTH]:0))>>1);}
function tt_MkTipContent(a)
{if(tt_t2t)
{if(tt_aV[COPYCONTENT])
tt_sContent=tt_t2t.innerHTML;else
tt_sContent="";}
else
tt_sContent=a[0];tt_ExtCallFncs(0,"CreateContentString");}
function tt_MkTipSubDivs()
{var sCss='position:relative;margin:0px;padding:0px;border-width:0px;left:0px;top:0px;line-height:normal;width:auto;',sTbTrTd=' cellspacing=0 cellpadding=0 border=0 style="'+sCss+'"><tbody style="'+sCss+'"><tr><td ';tt_aElt[0].innerHTML=(''
+(tt_aV[TITLE].length?('<div id="WzTiTl" style="position:relative;z-index:1;">'
+'<table id="WzTiTlTb"'+sTbTrTd+'id="WzTiTlI" style="'+sCss+'">'
+tt_aV[TITLE]
+'</td>'
+(tt_aV[CLOSEBTN]?('<td align="right" style="'+sCss
+'text-align:right;">'
+'<span id="WzClOsE" style="padding-left:2px;padding-right:2px;'
+'cursor:'+(tt_ie?'hand':'pointer')
+';" onmouseover="tt_OnCloseBtnOver(1)" onmouseout="tt_OnCloseBtnOver(0)" onclick="tt_HideInit()">'
+tt_aV[CLOSEBTNTEXT]
+'</span></td>'):'')
+'</tr></tbody></table></div>'):'')
+'<div id="WzBoDy" style="position:relative;z-index:0;">'
+'<table'+sTbTrTd+'id="WzBoDyI" style="'+sCss+'">'
+tt_sContent
+'</td></tr></tbody></table></div>'
+(tt_aV[SHADOW]?('<div id="WzTtShDwR" style="position:absolute;overflow:hidden;"></div>'
+'<div id="WzTtShDwB" style="position:relative;overflow:hidden;"></div>'):''));tt_GetSubDivRefs();if(tt_t2t&&!tt_aV[COPYCONTENT])
{tt_t2tDad=tt_t2t.parentNode||tt_t2t.parentElement||tt_t2t.offsetParent||null;if(tt_t2tDad)
{tt_MovDomNode(tt_t2t,tt_t2tDad,tt_aElt[6]);tt_t2t.style.display="block";}}
tt_ExtCallFncs(0,"SubDivsCreated");}
function tt_GetSubDivRefs()
{var aId=new Array("WzTiTl","WzTiTlTb","WzTiTlI","WzClOsE","WzBoDy","WzBoDyI","WzTtShDwB","WzTtShDwR");for(var i=aId.length;i;--i)
tt_aElt[i]=tt_GetElt(aId[i-1]);}
function tt_FormatTip()
{var css,w,iOffY,iOffSh;if(tt_aV[TITLE].length)
{css=tt_aElt[1].style;css.background=tt_aV[TITLEBGCOLOR];css.paddingTop=(tt_aV[CLOSEBTN]?2:0)+"px";css.paddingBottom="1px";css.paddingLeft=css.paddingRight=tt_aV[PADDING]+"px";css=tt_aElt[3].style;css.color=tt_aV[TITLEFONTCOLOR];css.fontFamily=tt_aV[TITLEFONTFACE];css.fontSize=tt_aV[TITLEFONTSIZE];css.fontWeight="bold";css.textAlign=tt_aV[TITLEALIGN];if(tt_aElt[4])
{css.paddingRight=(tt_aV[PADDING]<<1)+"px";css=tt_aElt[4].style;css.background=tt_aV[CLOSEBTNCOLORS][0];css.color=tt_aV[CLOSEBTNCOLORS][1];css.fontFamily=tt_aV[TITLEFONTFACE];css.fontSize=tt_aV[TITLEFONTSIZE];css.fontWeight="bold";}
if(tt_aV[WIDTH]>0)
tt_w=tt_aV[WIDTH]+((tt_aV[PADDING]+tt_aV[BORDERWIDTH])<<1);else
{tt_w=tt_GetDivW(tt_aElt[3])+tt_GetDivW(tt_aElt[4]);if(tt_aElt[4])
tt_w+=tt_aV[PADDING];}
iOffY=-tt_aV[BORDERWIDTH];}
else
{tt_w=0;iOffY=0;}
css=tt_aElt[5].style;css.top=iOffY+"px";if(tt_aV[BORDERWIDTH])
{css.borderColor=tt_aV[BORDERCOLOR];css.borderStyle=tt_aV[BORDERSTYLE];css.borderWidth=tt_aV[BORDERWIDTH]+"px";}
if(tt_aV[BGCOLOR].length)
css.background=tt_aV[BGCOLOR];if(tt_aV[BGIMG].length)
css.backgroundImage="url("+tt_aV[BGIMG]+")";css.padding=tt_aV[PADDING]+"px";css.textAlign=tt_aV[TEXTALIGN];css=tt_aElt[6].style;css.color=tt_aV[FONTCOLOR];css.fontFamily=tt_aV[FONTFACE];css.fontSize=tt_aV[FONTSIZE];css.fontWeight=tt_aV[FONTWEIGHT];css.background="";css.textAlign=tt_aV[TEXTALIGN];if(tt_aV[WIDTH]>0)
w=tt_aV[WIDTH]+((tt_aV[PADDING]+tt_aV[BORDERWIDTH])<<1);else
w=tt_GetDivW(tt_aElt[6])+((tt_aV[PADDING]+tt_aV[BORDERWIDTH])<<1);if(w>tt_w)
tt_w=w;if(tt_aV[SHADOW])
{tt_w+=tt_aV[SHADOWWIDTH];iOffSh=Math.floor((tt_aV[SHADOWWIDTH]*4)/3);css=tt_aElt[7].style;css.top=iOffY+"px";css.left=iOffSh+"px";css.width=(tt_w-iOffSh-tt_aV[SHADOWWIDTH])+"px";css.height=tt_aV[SHADOWWIDTH]+"px";css.background=tt_aV[SHADOWCOLOR];css=tt_aElt[8].style;css.top=iOffSh+"px";css.left=(tt_w-tt_aV[SHADOWWIDTH])+"px";css.width=tt_aV[SHADOWWIDTH]+"px";css.background=tt_aV[SHADOWCOLOR];}
else
iOffSh=0;tt_SetTipOpa(tt_aV[FADEIN]?0:tt_aV[OPACITY]);tt_FixSize(iOffY,iOffSh);}
function tt_FixSize(iOffY,iOffSh)
{var wIn,wOut,i;tt_aElt[0].style.width=tt_w+"px";tt_aElt[0].style.pixelWidth=tt_w;wOut=tt_w-((tt_aV[SHADOW])?tt_aV[SHADOWWIDTH]:0);wIn=wOut;if(!tt_bBoxOld)
wIn-=((tt_aV[PADDING]+tt_aV[BORDERWIDTH])<<1);tt_aElt[5].style.width=wIn+"px";if(tt_aElt[1])
{wIn=wOut-(tt_aV[PADDING]<<1);if(!tt_bBoxOld)
wOut=wIn;tt_aElt[1].style.width=wOut+"px";tt_aElt[2].style.width=wIn+"px";}
tt_h=tt_GetDivH(tt_aElt[0])+iOffY;if(tt_aElt[8])
tt_aElt[8].style.height=(tt_h-iOffSh)+"px";i=tt_aElt.length-1;if(tt_aElt[i])
{tt_aElt[i].style.width=tt_w+"px";tt_aElt[i].style.height=tt_h+"px";}}
function tt_DeAlt(el)
{var aKid;if(el.alt)
el.alt="";if(el.title)
el.title="";aKid=el.childNodes||el.children||null;if(aKid)
{for(var i=aKid.length;i;)
tt_DeAlt(aKid[--i]);}}
function tt_OpDeHref(el)
{if(!tt_op)
return;if(tt_elDeHref)
tt_OpReHref();while(el)
{if(el.hasAttribute("href"))
{el.t_href=el.getAttribute("href");el.t_stats=window.status;el.removeAttribute("href");el.style.cursor="hand";tt_AddEvtFnc(el,"mousedown",tt_OpReHref);window.status=el.t_href;tt_elDeHref=el;break;}
el=el.parentElement;}}
function tt_ShowInit()
{tt_tShow.Timer("tt_Show()",tt_aV[DELAY],true);if(tt_aV[CLICKCLOSE])
tt_AddEvtFnc(document,"mouseup",tt_HideInit);}
function tt_OverInit(e)
{tt_over=e.target||e.srcElement;tt_DeAlt(tt_over);tt_OpDeHref(tt_over);tt_AddRemOutFnc(true);}
function tt_Show()
{var css=tt_aElt[0].style;css.zIndex=Math.max((window.dd&&dd.z)?(dd.z+2):0,1010);if(tt_aV[STICKY]||!tt_aV[FOLLOWMOUSE])
tt_iState&=~0x4;if(tt_aV[DURATION]>0)
tt_tDurt.Timer("tt_HideInit()",tt_aV[DURATION],true);tt_ExtCallFncs(0,"Show")
css.visibility="visible";tt_iState|=0x2;if(tt_aV[FADEIN])
tt_Fade(0,0,tt_aV[OPACITY],Math.round(tt_aV[FADEIN]/tt_aV[FADEINTERVAL]));tt_ShowIfrm();}
function tt_ShowIfrm()
{if(tt_ie56)
{var ifrm=tt_aElt[tt_aElt.length-1];if(ifrm)
{var css=ifrm.style;css.zIndex=tt_aElt[0].style.zIndex-1;css.display="block";}}}
function tt_Move(e)
{e=window.event||e;if(e)
{tt_musX=tt_GetEvtX(e);tt_musY=tt_GetEvtY(e);}
if(tt_iState)
{if(!tt_over&&e)
tt_OverInit(e);if(tt_iState&0x4)
{if(!tt_op&&!tt_ie)
{if(tt_bWait)
return;tt_bWait=true;tt_tWaitMov.Timer("tt_bWait = false;",1,true);}
if(tt_aV[FIX])
{tt_iState&=~0x4;tt_SetTipPos(tt_aV[FIX][0],tt_aV[FIX][1]);}
else if(!tt_ExtCallFncs(e,"MoveBefore"))
tt_SetTipPos(tt_PosX(),tt_PosY());tt_ExtCallFncs([tt_musX,tt_musY],"MoveAfter")}}}
function tt_PosX()
{var x;x=tt_musX;if(tt_aV[LEFT])
x-=tt_w+tt_aV[OFFSETX]-(tt_aV[SHADOW]?tt_aV[SHADOWWIDTH]:0);else
x+=tt_aV[OFFSETX];if(x>tt_maxPosX)
x=tt_maxPosX;return((x<tt_scrlX)?tt_scrlX:x);}
function tt_PosY()
{var y;if(tt_aV[ABOVE]&&(!tt_bJmpVert||tt_CalcPosYAbove()>=tt_scrlY+16))
y=tt_DoPosYAbove();else if(!tt_aV[ABOVE]&&tt_bJmpVert&&tt_CalcPosYBelow()>tt_maxPosY-16)
y=tt_DoPosYAbove();else
y=tt_DoPosYBelow();if(y>tt_maxPosY)
y=tt_DoPosYAbove();if(y<tt_scrlY)
y=tt_DoPosYBelow();return y;}
function tt_DoPosYBelow()
{tt_bJmpVert=tt_aV[ABOVE];return tt_CalcPosYBelow();}
function tt_DoPosYAbove()
{tt_bJmpVert=!tt_aV[ABOVE];return tt_CalcPosYAbove();}
function tt_CalcPosYBelow()
{return(tt_musY+tt_aV[OFFSETY]);}
function tt_CalcPosYAbove()
{var dy=tt_aV[OFFSETY]-(tt_aV[SHADOW]?tt_aV[SHADOWWIDTH]:0);if(tt_aV[OFFSETY]>0&&dy<=0)
dy=1;return(tt_musY-tt_h-dy);}
function tt_OnOut()
{tt_AddRemOutFnc(false);if(!(tt_aV[STICKY]&&(tt_iState&0x2)))
tt_HideInit();}
function tt_HideInit()
{tt_ExtCallFncs(0,"HideInit");tt_iState&=~0x4;if(tt_flagOpa&&tt_aV[FADEOUT])
{tt_tFade.EndTimer();if(tt_opa)
{var n=Math.round(tt_aV[FADEOUT]/(tt_aV[FADEINTERVAL]*(tt_aV[OPACITY]/tt_opa)));tt_Fade(tt_opa,tt_opa,0,n);return;}}
tt_tHide.Timer("tt_Hide();",1,false);}
function tt_OpReHref()
{if(tt_elDeHref)
{tt_elDeHref.setAttribute("href",tt_elDeHref.t_href);tt_RemEvtFnc(tt_elDeHref,"mousedown",tt_OpReHref);window.status=tt_elDeHref.t_stats;tt_elDeHref=null;}}
function tt_Fade(a,now,z,n)
{if(n)
{now+=Math.round((z-now)/n);if((z>a)?(now>=z):(now<=z))
now=z;else
tt_tFade.Timer("tt_Fade("
+a+","+now+","+z+","+(n-1)
+")",tt_aV[FADEINTERVAL],true);}
now?tt_SetTipOpa(now):tt_Hide();}
function tt_SetTipOpa(opa)
{tt_SetOpa(tt_aElt[5].style,opa);if(tt_aElt[1])
tt_SetOpa(tt_aElt[1].style,opa);if(tt_aV[SHADOW])
{opa=Math.round(opa*0.8);tt_SetOpa(tt_aElt[7].style,opa);tt_SetOpa(tt_aElt[8].style,opa);}}
function tt_OnCloseBtnOver(iOver)
{var css=tt_aElt[4].style;iOver<<=1;css.background=tt_aV[CLOSEBTNCOLORS][iOver];css.color=tt_aV[CLOSEBTNCOLORS][iOver+1];}
function tt_Int(x)
{var y;return(isNaN(y=parseInt(x))?0:y);}
function tt_AddRemOutFnc(bAdd)
{var PSet=bAdd?tt_AddEvtFnc:tt_RemEvtFnc;if(bAdd!=tt_AddRemOutFnc.bOn)
{PSet(tt_over,"mouseout",tt_OnOut);tt_AddRemOutFnc.bOn=bAdd;if(!bAdd)
tt_OpReHref();}}
tt_AddRemOutFnc.bOn=false;Number.prototype.Timer=function(s,iT,bUrge)
{if(!this.value||bUrge)
this.value=window.setTimeout(s,iT);}
Number.prototype.EndTimer=function()
{if(this.value)
{window.clearTimeout(this.value);this.value=0;}}
function tt_SetOpa(css,opa)
{tt_opa=opa;if(tt_flagOpa==1)
{if(opa<100)
{var bVis=css.visibility!="hidden";css.zoom="100%";if(!bVis)
css.visibility="visible";css.filter="alpha(opacity="+opa+")";if(!bVis)
css.visibility="hidden";}
else
css.filter="";}
else
{opa/=100.0;switch(tt_flagOpa)
{case 2:css.KhtmlOpacity=opa;break;case 3:css.KHTMLOpacity=opa;break;case 4:css.MozOpacity=opa;break;case 5:css.opacity=opa;break;}}}
function tt_MovDomNode(el,dadFrom,dadTo)
{if(dadFrom)
dadFrom.removeChild(el);if(dadTo)
dadTo.appendChild(el);}
function tt_Err(sErr)
{if(tt_Debug)
alert("Tooltip Script Error Message:\n\n"+sErr);}
function tt_ExtCmdEnum()
{var s;for(var i in config)
{s="window."+i.toString().toUpperCase();if(eval("typeof("+s+") == tt_u"))
{eval(s+" = "+tt_aV.length);tt_aV[tt_aV.length]=null;}}}
function tt_ExtCallFncs(arg,sFnc)
{var b=false;for(var i=tt_aExt.length;i;)
{--i;var fnc=tt_aExt[i]["On"+sFnc];if(fnc&&fnc(arg))
b=true;}
return b;}
tt_Init();
var s5_columns_equalizer=new Class({initialize:function(elements,stop,prevent){this.elements=$$(elements);},equalize:function(hw){if(!hw){hw='height';}
var max=0,prop=(typeof document.body.style.maxHeight!='undefined'?'min-':'')+hw;offset='offset'+hw.capitalize();this.elements.each(function(element,i){var calc=element[offset];if(calc>max){max=calc;}},this);this.elements.each(function(element,i){element.setStyle(prop,max-(element[offset]-element.getStyle(hw).replace('px','')));});return max;}});var s5_resize_columns_small_tablets_screen_size="yes";var s5_screen_width=0;function s5_load_resize_columns(){s5_screen_width=document.body.offsetWidth;if(s5_resize_columns_small_tablets=="single"){if(document.body.offsetWidth<=750){var s5_remove_resize=document.getElementById("s5_columns_wrap").getElementsByTagName("DIV");for(var s5_remove_resize_y=0;s5_remove_resize_y<s5_remove_resize.length;s5_remove_resize_y++){if(s5_remove_resize[s5_remove_resize_y].className.indexOf("s5_resize")>=0){s5_remove_resize[s5_remove_resize_y].style.minHeight="1px";}}}}
if(document.body.offsetWidth<=580){var s5_remove_resize=document.getElementById("s5_body").getElementsByTagName("DIV");for(var s5_remove_resize_y=0;s5_remove_resize_y<s5_remove_resize.length;s5_remove_resize_y++){if(s5_remove_resize[s5_remove_resize_y].className.indexOf("s5_resize")>=0){s5_remove_resize[s5_remove_resize_y].style.minHeight="1px";}}}
if(document.body.offsetWidth>580){if(s5_resize_columns_small_tablets=="single"&&document.body.offsetWidth<=750){s5_resize_columns_small_tablets_screen_size="no";}
else{s5_resize_columns_small_tablets_screen_size="yes";}
if(document.getElementById("s5_columns_wrap")&&s5_resize_columns_small_tablets_screen_size=="yes"){var s5_resize_center_columns=document.getElementById("s5_columns_wrap").getElementsByTagName("DIV");for(var s5_resize_center_columns_y=0;s5_resize_center_columns_y<s5_resize_center_columns.length;s5_resize_center_columns_y++){if(s5_resize_center_columns[s5_resize_center_columns_y].id=="s5_center_column_wrap_inner"||s5_resize_center_columns[s5_resize_center_columns_y].id=="s5_left_column_wrap"||s5_resize_center_columns[s5_resize_center_columns_y].id=="s5_right_column_wrap"){s5_resize_center_columns[s5_resize_center_columns_y].style.minHeight="1px";if(s5_resize_center_columns[s5_resize_center_columns_y].className==""){s5_resize_center_columns[s5_resize_center_columns_y].className="s5_resize_center_columns";}
else{var s5_resize_classname=s5_resize_center_columns[s5_resize_center_columns_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_center_columns[s5_resize_center_columns_y].className="s5_resize_center_columns "+s5_resize_center_columns[s5_resize_center_columns_y].className;}}}}}
if(s5_resize_columns=="all"){if(document.getElementById("s5_top_row1")){var s5_resize_top_row1=document.getElementById("s5_top_row1").getElementsByTagName("DIV");for(var s5_resize_top_row1_y=0;s5_resize_top_row1_y<s5_resize_top_row1.length;s5_resize_top_row1_y++){if(s5_resize_top_row1[s5_resize_top_row1_y].className.indexOf("s5_resize_top_row1")>=0){s5_resize_top_row1[s5_resize_top_row1_y].style.minHeight="1px";}
if(s5_resize_top_row1[s5_resize_top_row1_y].className=="s5_module_box_2"){if(s5_resize_top_row1[s5_resize_top_row1_y].className==""){s5_resize_top_row1[s5_resize_top_row1_y].className="s5_resize_top_row1";}
else{var s5_resize_classname=s5_resize_top_row1[s5_resize_top_row1_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_top_row1[s5_resize_top_row1_y].className="s5_resize_top_row1 "+s5_resize_top_row1[s5_resize_top_row1_y].className;}}}}}
if(document.getElementById("s5_top_row2")){var s5_resize_top_row2=document.getElementById("s5_top_row2").getElementsByTagName("DIV");for(var s5_resize_top_row2_y=0;s5_resize_top_row2_y<s5_resize_top_row2.length;s5_resize_top_row2_y++){if(s5_resize_top_row2[s5_resize_top_row2_y].className.indexOf("s5_resize_top_row2")>=0){s5_resize_top_row2[s5_resize_top_row2_y].style.minHeight="1px";}
if(s5_resize_top_row2[s5_resize_top_row2_y].className=="s5_module_box_2"){if(s5_resize_top_row2[s5_resize_top_row2_y].className==""){s5_resize_top_row2[s5_resize_top_row2_y].className="s5_resize_top_row2";}
else{var s5_resize_classname=s5_resize_top_row2[s5_resize_top_row2_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_top_row2[s5_resize_top_row2_y].className="s5_resize_top_row2 "+s5_resize_top_row2[s5_resize_top_row2_y].className;}}}}}
if(document.getElementById("s5_top_row3")){var s5_resize_top_row3=document.getElementById("s5_top_row3").getElementsByTagName("DIV");for(var s5_resize_top_row3_y=0;s5_resize_top_row3_y<s5_resize_top_row3.length;s5_resize_top_row3_y++){if(s5_resize_top_row3[s5_resize_top_row3_y].className.indexOf("s5_resize_top_row3")>=0){s5_resize_top_row3[s5_resize_top_row3_y].style.minHeight="1px";}
if(s5_resize_top_row3[s5_resize_top_row3_y].className=="s5_module_box_2"){if(s5_resize_top_row3[s5_resize_top_row3_y].className==""){s5_resize_top_row3[s5_resize_top_row3_y].className="s5_resize_top_row3";}
else{var s5_resize_classname=s5_resize_top_row3[s5_resize_top_row3_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_top_row3[s5_resize_top_row3_y].className="s5_resize_top_row3 "+s5_resize_top_row3[s5_resize_top_row3_y].className;}}}}}
if(document.getElementById("s5_above_columns_inner")){var s5_resize_above_columns_inner=document.getElementById("s5_above_columns_inner").getElementsByTagName("DIV");for(var s5_resize_above_columns_inner_y=0;s5_resize_above_columns_inner_y<s5_resize_above_columns_inner.length;s5_resize_above_columns_inner_y++){if(s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].className.indexOf("s5_resize_above_columns_inner")>=0){s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].style.minHeight="1px";}
if(s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].className=="s5_module_box_2"){if(s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].className==""){s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].className="s5_resize_above_columns_inner";}
else{var s5_resize_classname=s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].className="s5_resize_above_columns_inner "+s5_resize_above_columns_inner[s5_resize_above_columns_inner_y].className;}}}}}
if(document.getElementById("s5_middle_top")){var s5_resize_middle_top=document.getElementById("s5_middle_top").getElementsByTagName("DIV");for(var s5_resize_middle_top_y=0;s5_resize_middle_top_y<s5_resize_middle_top.length;s5_resize_middle_top_y++){if(s5_resize_middle_top[s5_resize_middle_top_y].className.indexOf("s5_resize_middle_top")>=0){s5_resize_middle_top[s5_resize_middle_top_y].style.minHeight="1px";}
if(s5_resize_middle_top[s5_resize_middle_top_y].className=="s5_module_box_2"){if(s5_resize_middle_top[s5_resize_middle_top_y].className==""){s5_resize_middle_top[s5_resize_middle_top_y].className="s5_resize_middle_top";}
else{var s5_resize_classname=s5_resize_middle_top[s5_resize_middle_top_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_middle_top[s5_resize_middle_top_y].className="s5_resize_middle_top "+s5_resize_middle_top[s5_resize_middle_top_y].className;}}}}}
if(document.getElementById("s5_above_body")){var s5_resize_above_body=document.getElementById("s5_above_body").getElementsByTagName("DIV");for(var s5_resize_above_body_y=0;s5_resize_above_body_y<s5_resize_above_body.length;s5_resize_above_body_y++){if(s5_resize_above_body[s5_resize_above_body_y].className.indexOf("s5_resize_above_body")>=0){s5_resize_above_body[s5_resize_above_body_y].style.minHeight="1px";}
if(s5_resize_above_body[s5_resize_above_body_y].className=="s5_fourdivs_4"){if(s5_resize_above_body[s5_resize_above_body_y].className==""){s5_resize_above_body[s5_resize_above_body_y].className="s5_resize_above_body";}
else{var s5_resize_classname=s5_resize_above_body[s5_resize_above_body_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_above_body[s5_resize_above_body_y].className="s5_resize_above_body "+s5_resize_above_body[s5_resize_above_body_y].className;}}}}}
if(document.getElementById("s5_below_body")){var s5_resize_below_body=document.getElementById("s5_below_body").getElementsByTagName("DIV");for(var s5_resize_below_body_y=0;s5_resize_below_body_y<s5_resize_below_body.length;s5_resize_below_body_y++){if(s5_resize_below_body[s5_resize_below_body_y].className.indexOf("s5_resize_below_body")>=0){s5_resize_below_body[s5_resize_below_body_y].style.minHeight="1px";}
if(s5_resize_below_body[s5_resize_below_body_y].className=="s5_fourdivs_4"){if(s5_resize_below_body[s5_resize_below_body_y].className==""){s5_resize_below_body[s5_resize_below_body_y].className="s5_resize_below_body";}
else{var s5_resize_classname=s5_resize_below_body[s5_resize_below_body_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_below_body[s5_resize_below_body_y].className="s5_resize_below_body "+s5_resize_below_body[s5_resize_below_body_y].className;}}}}}
if(document.getElementById("s5_middle_bottom")){var s5_resize_middle_bottom=document.getElementById("s5_middle_bottom").getElementsByTagName("DIV");for(var s5_resize_middle_bottom_y=0;s5_resize_middle_bottom_y<s5_resize_middle_bottom.length;s5_resize_middle_bottom_y++){if(s5_resize_middle_bottom[s5_resize_middle_bottom_y].className.indexOf("s5_resize_middle_bottom")>=0){s5_resize_middle_bottom[s5_resize_middle_bottom_y].style.minHeight="1px";}
if(s5_resize_middle_bottom[s5_resize_middle_bottom_y].className=="s5_module_box_2"){if(s5_resize_middle_bottom[s5_resize_middle_bottom_y].className==""){s5_resize_middle_bottom[s5_resize_middle_bottom_y].className="s5_resize_middle_bottom";}
else{var s5_resize_classname=s5_resize_middle_bottom[s5_resize_middle_bottom_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_middle_bottom[s5_resize_middle_bottom_y].className="s5_resize_middle_bottom "+s5_resize_middle_bottom[s5_resize_middle_bottom_y].className;}}}}}
if(document.getElementById("s5_below_columns_inner")){var s5_resize_below_columns_inner=document.getElementById("s5_below_columns_inner").getElementsByTagName("DIV");for(var s5_resize_below_columns_inner_y=0;s5_resize_below_columns_inner_y<s5_resize_below_columns_inner.length;s5_resize_below_columns_inner_y++){if(s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].className.indexOf("s5_resize_below_columns_inner")>=0){s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].style.minHeight="1px";}
if(s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].className=="s5_module_box_2"){if(s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].className==""){s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].className="s5_resize_below_columns_inner";}
else{var s5_resize_classname=s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].className="s5_resize_below_columns_inner "+s5_resize_below_columns_inner[s5_resize_below_columns_inner_y].className;}}}}}
if(document.getElementById("s5_bottom_row1")){var s5_resize_bottom_row1=document.getElementById("s5_bottom_row1").getElementsByTagName("DIV");for(var s5_resize_bottom_row1_y=0;s5_resize_bottom_row1_y<s5_resize_bottom_row1.length;s5_resize_bottom_row1_y++){if(s5_resize_bottom_row1[s5_resize_bottom_row1_y].className.indexOf("s5_resize_bottom_row1")>=0){s5_resize_bottom_row1[s5_resize_bottom_row1_y].style.minHeight="1px";}
if(s5_resize_bottom_row1[s5_resize_bottom_row1_y].className=="s5_module_box_2"){if(s5_resize_bottom_row1[s5_resize_bottom_row1_y].className==""){s5_resize_bottom_row1[s5_resize_bottom_row1_y].className="s5_resize_bottom_row1";}
else{var s5_resize_classname=s5_resize_bottom_row1[s5_resize_bottom_row1_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_bottom_row1[s5_resize_bottom_row1_y].className="s5_resize_bottom_row1 "+s5_resize_bottom_row1[s5_resize_bottom_row1_y].className;}}}}}
if(document.getElementById("s5_bottom_row2")){var s5_resize_bottom_row2=document.getElementById("s5_bottom_row2").getElementsByTagName("DIV");for(var s5_resize_bottom_row2_y=0;s5_resize_bottom_row2_y<s5_resize_bottom_row2.length;s5_resize_bottom_row2_y++){if(s5_resize_bottom_row2[s5_resize_bottom_row2_y].className.indexOf("s5_resize_bottom_row2")>=0){s5_resize_bottom_row2[s5_resize_bottom_row2_y].style.minHeight="1px";}
if(s5_resize_bottom_row2[s5_resize_bottom_row2_y].className=="s5_module_box_2"){if(s5_resize_bottom_row2[s5_resize_bottom_row2_y].className==""){s5_resize_bottom_row2[s5_resize_bottom_row2_y].className="s5_resize_bottom_row2";}
else{var s5_resize_classname=s5_resize_bottom_row2[s5_resize_bottom_row2_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_bottom_row2[s5_resize_bottom_row2_y].className="s5_resize_bottom_row2 "+s5_resize_bottom_row2[s5_resize_bottom_row2_y].className;}}}}}
if(document.getElementById("s5_bottom_row3")){var s5_resize_bottom_row3=document.getElementById("s5_bottom_row3").getElementsByTagName("DIV");for(var s5_resize_bottom_row3_y=0;s5_resize_bottom_row3_y<s5_resize_bottom_row3.length;s5_resize_bottom_row3_y++){if(s5_resize_bottom_row3[s5_resize_bottom_row3_y].className.indexOf("s5_resize_bottom_row3")>=0){s5_resize_bottom_row3[s5_resize_bottom_row3_y].style.minHeight="1px";}
if(s5_resize_bottom_row3[s5_resize_bottom_row3_y].className=="s5_module_box_2"){if(s5_resize_bottom_row3[s5_resize_bottom_row3_y].className==""){s5_resize_bottom_row3[s5_resize_bottom_row3_y].className="s5_resize_bottom_row3";}
else{var s5_resize_classname=s5_resize_bottom_row3[s5_resize_bottom_row3_y].className;if(s5_resize_classname.indexOf("s5_resize")<0){s5_resize_bottom_row3[s5_resize_bottom_row3_y].className="s5_resize_bottom_row3 "+s5_resize_bottom_row3[s5_resize_bottom_row3_y].className;}}}}}}
if(document.getElementById("s5_columns_wrap")&&s5_resize_columns_small_tablets_screen_size=="yes"){new s5_columns_equalizer('.s5_resize_center_columns').equalize('height');}
if(s5_resize_columns=="all"){new s5_columns_equalizer('.s5_resize_top_row1').equalize('height');new s5_columns_equalizer('.s5_resize_top_row2').equalize('height');new s5_columns_equalizer('.s5_resize_top_row3').equalize('height');new s5_columns_equalizer('.s5_resize_above_columns_inner').equalize('height');new s5_columns_equalizer('.s5_resize_middle_top').equalize('height');new s5_columns_equalizer('.s5_resize_above_body').equalize('height');new s5_columns_equalizer('.s5_resize_below_body').equalize('height');new s5_columns_equalizer('.s5_resize_middle_bottom').equalize('height');new s5_columns_equalizer('.s5_resize_below_columns_inner').equalize('height');new s5_columns_equalizer('.s5_resize_bottom_row1').equalize('height');new s5_columns_equalizer('.s5_resize_bottom_row2').equalize('height');new s5_columns_equalizer('.s5_resize_bottom_row3').equalize('height');}}}
window.addEvent('domready',function(){window.setTimeout(s5_load_resize_columns,s5_resize_columns_delay);window.setTimeout(s5_load_resize_columns,2000);window.setTimeout(s5_load_resize_columns,2500);window.setTimeout(s5_load_resize_columns,3500);});function s5_screen_width_check(){if(s5_screen_width!=document.body.offsetWidth){s5_load_resize_columns();}}
$(window).addEvent('resize',s5_screen_width_check);<?php exit();?>