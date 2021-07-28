
var rhost = '/test-tasks'; // temp
var https_host = 'https://'+window.location.hostname+rhost+'/', _protocol = document.location.protocol;
if (_protocol!='https') 
{
    let sq = '',sqI = location.href.indexOf('?');
    if (sqI!=-1) sq = location.href.substring(sqI+1);
    
    if (location.href!=https_host+'?'+sq)
    location.href = https_host+'?'+sq;
}


function getid(id)
{
 return document.getElementById(id);
}

function gebc(object)
{
 return document.getElementsByClassName(object);
}

// sort 'date'
function sort_date(a, b) {
  if (a['date'] < b['date']) return 1;
  else if (a['date'] > b['date']) return -1;
  else return 0;
} 

function screenSize() {
  var w, h;
  w = (window.innerWidth ? window.innerWidth : (document.documentElement.clientWidth ? document.documentElement.clientWidth : document.body.offsetWidth));
  h = (window.innerHeight ? window.innerHeight : (document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.offsetHeight));
  return {w:w, h:h}; 
}

 function cv_url(pageurl)
 {
  try
  {
	  parent.window.history.replaceState({path:pageurl},'',pageurl);
  }
  catch(e) { }
 }

 function validateEmail(email) {
  const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

// module_md5.js
function utf8_encode(e){e=e.replace(/\r\n/g,"\n");for(var h="",q=0;q<e.length;q++){var l=e.charCodeAt(q);128>l?h+=String.fromCharCode(l):(127<l&&2048>l?h+=String.fromCharCode(l>>6|192):(h+=String.fromCharCode(l>>12|224),h+=String.fromCharCode(l>>6&63|128)),h+=String.fromCharCode(l&63|128))}return h}
function md5(e){var h=function(g,m){var k=g&2147483648;var p=m&2147483648;var r=g&1073741824;var t=m&1073741824;var n=(g&1073741823)+(m&1073741823);return r&t?n^2147483648^k^p:r|t?n&1073741824?n^3221225472^k^p:n^1073741824^k^p:n^k^p},q=function(g,m,k,p,r,t,n){g=h(g,h(h(m&k|~m&p,r),n));return h(g<<t|g>>>32-t,m)},l=function(g,m,k,p,r,t,n){g=h(g,h(h(m&p|k&~p,r),n));return h(g<<t|g>>>32-t,m)},u=function(g,m,k,p,r,t,n){g=h(g,h(h(m^k^p,r),n));return h(g<<t|g>>>32-t,m)},v=function(g,m,k,p,r,t,n){g=h(g,h(h(k^
(m|~p),r),n));return h(g<<t|g>>>32-t,m)},w=function(g){var m="",k;for(k=0;3>=k;k++){var p=g>>>8*k&255;p="0"+p.toString(16);m+=p.substr(p.length-2,2)}return m},f=[];e=this.utf8_encode(e);f=function(g){var m=g.length;var k=m+8;for(var p=16*((k-k%64)/64+1),r=Array(p-1),t,n=0;n<m;)k=(n-n%4)/4,t=n%4*8,r[k]|=g.charCodeAt(n)<<t,n++;k=(n-n%4)/4;r[k]|=128<<n%4*8;r[p-2]=m<<3;r[p-1]=m>>>29;return r}(e);var a=1732584193;var b=4023233417;var c=2562383102;var d=271733878;for(e=0;e<f.length;e+=16){var x=a;var y=
b;var z=c;var A=d;a=q(a,b,c,d,f[e+0],7,3614090360);d=q(d,a,b,c,f[e+1],12,3905402710);c=q(c,d,a,b,f[e+2],17,606105819);b=q(b,c,d,a,f[e+3],22,3250441966);a=q(a,b,c,d,f[e+4],7,4118548399);d=q(d,a,b,c,f[e+5],12,1200080426);c=q(c,d,a,b,f[e+6],17,2821735955);b=q(b,c,d,a,f[e+7],22,4249261313);a=q(a,b,c,d,f[e+8],7,1770035416);d=q(d,a,b,c,f[e+9],12,2336552879);c=q(c,d,a,b,f[e+10],17,4294925233);b=q(b,c,d,a,f[e+11],22,2304563134);a=q(a,b,c,d,f[e+12],7,1804603682);d=q(d,a,b,c,f[e+13],12,4254626195);c=q(c,d,
a,b,f[e+14],17,2792965006);b=q(b,c,d,a,f[e+15],22,1236535329);a=l(a,b,c,d,f[e+1],5,4129170786);d=l(d,a,b,c,f[e+6],9,3225465664);c=l(c,d,a,b,f[e+11],14,643717713);b=l(b,c,d,a,f[e+0],20,3921069994);a=l(a,b,c,d,f[e+5],5,3593408605);d=l(d,a,b,c,f[e+10],9,38016083);c=l(c,d,a,b,f[e+15],14,3634488961);b=l(b,c,d,a,f[e+4],20,3889429448);a=l(a,b,c,d,f[e+9],5,568446438);d=l(d,a,b,c,f[e+14],9,3275163606);c=l(c,d,a,b,f[e+3],14,4107603335);b=l(b,c,d,a,f[e+8],20,1163531501);a=l(a,b,c,d,f[e+13],5,2850285829);d=l(d,
a,b,c,f[e+2],9,4243563512);c=l(c,d,a,b,f[e+7],14,1735328473);b=l(b,c,d,a,f[e+12],20,2368359562);a=u(a,b,c,d,f[e+5],4,4294588738);d=u(d,a,b,c,f[e+8],11,2272392833);c=u(c,d,a,b,f[e+11],16,1839030562);b=u(b,c,d,a,f[e+14],23,4259657740);a=u(a,b,c,d,f[e+1],4,2763975236);d=u(d,a,b,c,f[e+4],11,1272893353);c=u(c,d,a,b,f[e+7],16,4139469664);b=u(b,c,d,a,f[e+10],23,3200236656);a=u(a,b,c,d,f[e+13],4,681279174);d=u(d,a,b,c,f[e+0],11,3936430074);c=u(c,d,a,b,f[e+3],16,3572445317);b=u(b,c,d,a,f[e+6],23,76029189);
a=u(a,b,c,d,f[e+9],4,3654602809);d=u(d,a,b,c,f[e+12],11,3873151461);c=u(c,d,a,b,f[e+15],16,530742520);b=u(b,c,d,a,f[e+2],23,3299628645);a=v(a,b,c,d,f[e+0],6,4096336452);d=v(d,a,b,c,f[e+7],10,1126891415);c=v(c,d,a,b,f[e+14],15,2878612391);b=v(b,c,d,a,f[e+5],21,4237533241);a=v(a,b,c,d,f[e+12],6,1700485571);d=v(d,a,b,c,f[e+3],10,2399980690);c=v(c,d,a,b,f[e+10],15,4293915773);b=v(b,c,d,a,f[e+1],21,2240044497);a=v(a,b,c,d,f[e+8],6,1873313359);d=v(d,a,b,c,f[e+15],10,4264355552);c=v(c,d,a,b,f[e+6],15,2734768916);
b=v(b,c,d,a,f[e+13],21,1309151649);a=v(a,b,c,d,f[e+4],6,4149444226);d=v(d,a,b,c,f[e+11],10,3174756917);c=v(c,d,a,b,f[e+2],15,718787259);b=v(b,c,d,a,f[e+9],21,3951481745);a=h(a,x);b=h(b,y);c=h(c,z);d=h(d,A)}return(w(a)+w(b)+w(c)+w(d)).toLowerCase()};
//========================== md5.js

function translite(e){var a="";e=e.toLowerCase();for(var d=[[".","-"],["@","@"],["_","-"],[" ","-"],["-","-"],["/","-"],["\\","-"],["0","0"],["1","1"],["2","2"],["3","3"],["4","4"],["5","5"],["6","6"],["7","7"],["8","8"],["9","9"],["quot1;","-"],["quot;","-"],["34;","-"],["39;","-"],["apos;","-"],["resh;","-"],["gt;","-"],["lt;","-"],["and;","-"],["amp;","-"],["'","-"],['"',"-"],["q","q"],["w","w"],["e","e"],["r","r"],["t","t"],["y","y"],["u","u"],["i","i"],["o","o"],["p","p"],["a","a"],["s","s"],
["d","d"],["f","f"],["g","g"],["h","h"],["j","j"],["k","k"],["l","l"],["z","z"],["x","x"],["c","c"],["v","v"],["b","b"],["n","n"],["m","m"],["Q","Q"],["W","W"],["E","E"],["R","R"],["T","T"],["Y","Y"],["U","U"],["I","I"],["O","O"],["P","P"],["A","A"],["S","S"],["D","D"],["F","F"],["G","G"],["H","H"],["J","J"],["K","K"],["L","L"],["Z","Z"],["X","X"],["C","C"],["V","V"],["B","B"],["N","N"],["M","M"],["\u0430","a"],["\u0431","b"],["\u0432","v"],["\u0433","g"],["\u0434","d"],["\u0435","e"],["\u0436","zh"],
["\u0437","z"],["\u0438","i"],["\u0439","j"],["\u043a","k"],["\u043b","l"],["\u043c","m"],["\u043d","n"],["\u043e","o"],["\u043f","p"],["\u0440","r"],["\u0441","s"],["\u0442","t"],["\u0443","u"],["\u0444","f"],["\u044b","y"],["\u044d","je"],["\u0410","A"],["\u0411","B"],["\u0412","V"],["\u0413","G"],["\u0414","D"],["\u0415","E"],["\u0416","ZH"],["\u0417","Z"],["\u0418","I"],["\u0419","J"],["\u041a","K"],["\u041b","L"],["\u041c","M"],["\u041d","N"],["\u041e","O"],["\u041f","P"],["\u0420","R"],["\u0421",
"S"],["\u0422","T"],["\u0423","U"],["\u0424","F"],["\u042b","Y"],["\u042d","JE"],["\u0451","yo"],["\u0445","h"],["\u0446","c"],["\u0447","ch"],["\u0448","sh"],["\u0449","shh"],["\u044e","yu"],["\u044f","ya"],["\u0401","YO"],["\u0425","H"],["\u0426","C"],["\u0427","CH"],["\u0428","SH"],["\u0429","SHH"],["\u042e","YU"],["\u042f","YA"],["\u044c",""],["\u044a",""],["\u042c",""],["\u042a",""],["\u0456","i"],["\u0406","I"],["\u0457","i"],["\u0407","I"]],c=0,b,f;c<e.length;){b=0;for(f=-1;b<d.length;){if(e.substring(c,
c+d[b][0].length)==d[b][0]){f=b;c=c+d[b][0].length-1;break}b++}-1!=f&&(a+=d[f][1]);c++}a=a.replace(/--+/g,"-",a);"-"==a.charAt(0)&&(a=a.substring(1,a.length));"-"==a.charAt(a.length-1)&&(a=a.substring(0,a.length-1));return a};
