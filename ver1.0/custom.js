// save file enkripsi
function DownloadEnkripsi(text, name, type) {
  var a = document.getElementById("SimpanEnkripsi");
  var file = new Blob([text], {type: type});
  a.href = URL.createObjectURL(file);
  a.download = name;
}
// ---

// save file dekripsi
function DownloadDekripsi(text, name, type) {
  var a = document.getElementById("SimpanDekripsi");
  var file = new Blob([text], {type: type});
  a.href = URL.createObjectURL(file);
  a.download = name;
}
// ---

// save file kompresi
function DownloadKompresi(text, name, type) {
  var a = document.getElementById("SimpanKompresi");
  var file = new Blob([text], {type: type});
  a.href = URL.createObjectURL(file);
  a.download = name;

  // let byteChars = atob(text);
  // let byteNumbers = new Array(byteChars.length);
  // for (var i = 0; i < byteChars.length; i++) {
  //   byteNumbers[i] = byteChars.charCodeAt(i);
  // }
  // let byteArray = new Uint8Array(byteNumbers);
  // var data = new Blob([byteArray], {type: type});
  // saveAs(data, name);
}
// ---

function DownloadDekompresi(text, name, type) {
  var a = document.getElementById("SimpanDekompresi");
  var file = new Blob([text], {type: type});
  a.href = URL.createObjectURL(file);
  a.download = name;
}
// ---

// open file
var reader;  
function checkFileAPI() {
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        reader = new FileReader();
        return true; 
    } else {
        alert('The File APIs are not fully supported by your browser. Fallback required.');
        return false;
    }
}

function readText(filePath) {
  var output = ""; //placeholder for text output
  if(filePath.files && filePath.files[0]) {           
    reader.onload = function (e) {
      output = e.target.result;
      console.log(output);
          displayContents(output);
      };//end onload()
      reader.readAsText(filePath.files[0]);
  }//end if html5 filelist support
  else if(ActiveXObject && filePath) { //fallback to IE 6-8 support via ActiveX
    try {
      reader = new ActiveXObject("Scripting.FileSystemObject");
          var file = reader.OpenTextFile(filePath, 1); //ActiveX File Object
          output = file.ReadAll(); //text contents of file
          file.Close(); //close file "input stream"
          displayContents(output);
          console.log(output);
        } catch (e) {
          if (e.number == -2146827859) {
            alert('Unable to access local files due to browser security settings. ' + 
             'To overcome this, go to Tools->Internet Options->Security->Custom Level. ' + 
             'Find the setting for "Initialize and script ActiveX controls not marked as safe" and change it to "Enable" or "Prompt"'); 
          }
        }       
      }
  else { //this is where you could fallback to Java Applet, Flash or similar
    return false;
  }       
  return true;
}   

function displayContents(txt) {
    // var el = document.getElementById('main'); 
    // el.innerHTML = txt; //display output in DOM
    // alert(txt);
    $('.content.dekripsi .input textarea').val(txt);
    $('.content.kompresi .input textarea').val(txt);
    $('.content.dekompresi .input textarea').val(txt);
    output = txt;
}   
// ---

//styling JS
$(document).ready(function(){
  symbol = '~';

  // menu selector
  $(".menu h3").click(function(){
    $(".tabBlock").toggle('slow');
    $(this).toggleClass('active');
  });
  $("li.enkripsi").click(function(){
    $('.content').hide();
    $('.content.enkripsi').show();
  });
  $("li.dekripsi").click(function(){
    $('.content').hide();
    $('.content.dekripsi').show();
  });
  $("li.kompresi").click(function(){
    $('.content').hide();
    $('.content.kompresi').show();
  });
  $("li.dekompresi").click(function(){
    $('.content').hide();
    $('.content.dekompresi').show();
  });

  // Enkripsi whitespace
  $('.content.enkripsi .input button').click(function() {
    EnkripsiInput = $('textarea.text').val();
    SecretInput = $('textarea.secret').val();

    // proses enkripsi
    input = symbol+SecretInput;

    proses1 = text2Binary(input);
    console.log('jumlah bit string : '+proses1.length);
    proses2 = binarytowhitespace(proses1);
    result = EnkripsiInput+proses2;
    // ---
    console.log(proses1);

    $('.content.enkripsi .output textarea').val(result);

    DownloadEnkripsi(result, 'enkripsi.txt', 'text/plain');
  });
  // ---

  // dekripsi whitespace
  $('.content.dekripsi .input button').click(function() {
    var DekripsiInput = $('.content.dekripsi .input textarea').val();
    // console.log(DekripsiInput);

    SymbolToWhitespace = binarytowhitespace(text2Binary(symbol));
    // console.log(SymbolToWhitespace);
    // console.log(text2Binary(symbol));
    DeteksiSimbol = detect(DekripsiInput,SymbolToWhitespace);
    // console.log(DeteksiSimbol);

    // mengambil karakter setelah simbol untuk pesan rahasia
    PesanRahasia = DekripsiInput.substr(DeteksiSimbol);
    console.log(PesanRahasia);

    // mengambil karakter dari 0 sampai sebelum pesan rahasia
    teks = DekripsiInput.slice(0, DeteksiSimbol-8);
    console.log(teks);

    DekripsiPesanRahasia = binarytotext(whitespacetobinary(PesanRahasia));
    console.log(whitespacetobinary(PesanRahasia));
    hasil = teks+DekripsiPesanRahasia

    $('.content.dekripsi .output textarea').val(hasil);  

    DownloadDekripsi(hasil, 'dekripsi.txt', 'text/plain'); 
  });

  // kompresi shannon fano
  $('.content.kompresi .input button').click(function() {
    KompresiInput = $('.content.kompresi .input textarea').val();
    // console.log(KompresiInput);

    // Proses Kompresi
    Frekuensi = object2array2d(Frequency(KompresiInput));
    // console.log(KompresiInput);
    UrutkanFrekuensi = Frekuensi.sort(SortByFreq)
    // console.log('=====');
    // console.log(UrutkanFrekuensi);
    // document.getElementById('test').innerHTML = UrutkanFrekuensi;
    console.log('=====');
    TableSF = tableSF(UrutkanFrekuensi);
    console.log('=====');
    console.log(UrutkanFrekuensi);

    var sortable = [];
for (var vehicle in TableSF) {
    sortable.push([vehicle, TableSF[vehicle]]);
}

sortable.sort(function(a, b) {
    return b[1] - a[1];
  });
    
    console.log(sortable);
    console.log(TableSF);
    x = sortable;
    // document.getElementById('test').innerHTML = sortable;
    // console.log(x);
    // console.log('=====');
    // TableSFtoString = sortable.join();
    // console.log(TableSFtoString);  
    //     console.log('=====');
    // lengthHeader = TableSFtoString.length;
    // console.log('length : '+lengthHeader);
    // lengthHeader = lengthHeader+'*';
    // console.log('length+simbol : '+lengthHeader);
  

    HasilKompresi = encode(KompresiInput,TableSF);
    console.log('Jumlah karakter : '+HasilKompresi.length);
    // Dekompresi = decode(TableSF,HasilKompresi);
    // console.log(Dekompresi);

    
    //test modulus
    // test = '';
    // test2 = test.length%8;
    // alert(test2);

    sisa = HasilKompresi.length%8;
    alert(sisa);
    sisa = 8-sisa;
    alert(sisa);
    console.log(sisa);
    $('.content.kompresi .output textarea').val(HasilKompresi);

    header = sortable;
    console.log('header'+header);
    // alert(sisa);
    if (sisa !=8) {
      HasilKompresi = AddZero(HasilKompresi,sisa);
    console.log('KompresiPad : '+HasilKompresi.length);
    header = header+sisa+'*';
    }
    else {
      header = header+'0'+'*';
      alert('sisa 0')
    }
    
    

    // tampungKompresi = HasilKompresi;

    BitToASCII = binaryToString(HasilKompresi);
    // console.log(BitToASCII);
    // integer = Number(HasilKompresi);
    // console.log(integer);
    // test = text2Binary(KompresiInput);
    // console.log(test);
    // console.log(typeof BitToASCII);
    // binary = new Uint8Array(HasilKompresi);
    // console.log(binary);
    // var binObj = new BinaryObject(HasilKompresi);
    
    // test cari simbol header
    test = '123**123';
    testoutput = test.indexOf('*');
    console.log('testoutput : '+testoutput);

    HasilKompresi = header+HasilKompresi;
    console.log(HasilKompresi);

    $('.content.kompresi .output textarea').val(HasilKompresi);

// let byteChars = atob(HasilKompresi);
// let byteNumbers = new Array(byteChars.length);
// for (var i = 0; i < byteChars.length; i++) {
//   byteNumbers[i] = byteChars.charCodeAt(i);
// }
// let byteArray = new Uint8Array(byteNumbers);
// var data = new Blob([byteArray], {type: "application/octet-stream"});
// saveAs(data, "myfile.abc");
// test = '00110001001100100011001100110100001101010011011011110000';
// test2 = binarytotext(test);

    // test = AddBit(HasilKompresi);
    BitToASCII = header+BitToASCII
    DownloadKompresi(BitToASCII, 'kompresi.sf', 'application/octet-binary');
  });

  // dekompresi shannon fano
  $('.content.dekompresi .input button').click(function() {
    DekompresiInput = $('.content.dekompresi .input textarea').val();
    // console.log(DekompresiInput);
    alert(output);

    // Proses Dekompresi
    ASCIIToBit = stringToBinary(DekompresiInput);
    ASCIIToBit = output;

    // pemisah header dengan hasil kompresi
    headerSymbol = ASCIIToBit.indexOf('*');
    console.log('==========');
    header = ASCIIToBit.substring(0,headerSymbol);
    console.log('header : '+header);
    headerSF = header.substring(0,headerSymbol-1);
    content = ASCIIToBit.substr(headerSymbol+1,ASCIIToBit.length);
    console.log('content : '+content);

    sisa = ASCIIToBit.charAt(headerSymbol-1);
    alert(sisa);
    ASCIIToBit = content;

    // headerSymbol = ASCIIToBit.indexOf('*');
    // console.log('headerSymbol : '+headerSymbol);
    // headerLenght = ASCIIToBit.substring(0,headerSymbol);
    // console.log('headerLenght : '+headerLenght);

    // HeaderTableSF = ASCIIToBit.substring(4,headerLenght);
    // console.log('HeaderTableSF : '+HeaderTableSF);



    // console.log(ASCIIToBit);
    // HasilDecode = decode(TableSF,DekompresiInput);
    console.log(ASCIIToBit);
    console.log(sisa);
    
    
    tampungDekompresi = ASCIIToBit;

    // if (tampungDekompresi == tampungKompresi) {
    //   alert('sukses');
    // }
    // else {
    //   alert('gagal');
    // }

    headerSF = StringToArray2D(headerSF);
    console.log(headerSF);
//         var sortable = [];
// for (var vehicle in TableSF) {
//     sortable.push([vehicle, TableSF[vehicle]]);
// }

// sortable.sort(function(a, b) {
//     return b[1] - a[1];
//   });

    
//     console.log(sortable);

//     if (headerSF == sortable) {
//       alert('sukses table SF')
//     }
//     else {
//       alert('gagal table sf')
//     }
    ASCIIToBit = stringToBinary(ASCIIToBit);
    console.log('jumlah karakter : '+ASCIIToBit.length);
    if(sisa!=0) {
      ASCIIToBit = DeleteZero(ASCIIToBit,sisa);
    }
    console.log('ascii : '+ASCIIToBit.length);

    HasilDecode = decode2(headerSF,ASCIIToBit);
    console.log(HasilDecode);
    console.log(ASCIIToBit);
    // HasilDecode3 = decode2(x,ASCIIToBit);
    $('.content.dekompresi .output textarea').val(HasilDecode);

    DownloadDekompresi(HasilDecode, 'dekompresi.txt', 'text/plain');
  });
});


function stringToBinary(str, spaceSeparatedOctets) {
    function zeroPad(num) {
        return "00000000".slice(String(num).length) + num;
    }

    return str.replace(/[\s\S]/g, function(str) {
        str = zeroPad(str.charCodeAt().toString(2));
        return !1 == spaceSeparatedOctets ? str : str
    });
};

function binaryToString(str) {
    // Removes the spaces from the binary string
    str = str.replace(/\s+/g, '');
    // Pretty (correct) print binary (add a space every 8 characters)
    str = str.match(/.{1,8}/g).join(" ");

    var newBinary = str.split(" ");
    var binaryCode = [];

    for (i = 0; i < newBinary.length; i++) {
        binaryCode.push(String.fromCharCode(parseInt(newBinary[i], 2)));
    }
    
    return binaryCode.join("");
}


console.log(binarytotext('11'));

// menu dropdown
  function myFunction(x) {
    x.classList.toggle("change");
    $(".tabBlock").toggle('slow');
  }

function saveAs(blob, fileName) {
    var url = window.URL.createObjectURL(blob);

    var anchorElem = document.createElement("a");
    anchorElem.style = "display: none";
    anchorElem.href = url;
    anchorElem.download = fileName;

    document.body.appendChild(anchorElem);
    anchorElem.click();

    document.body.removeChild(anchorElem);

    // On Edge, revokeObjectURL should be called only after
    // a.click() has completed, atleast on EdgeHTML 15.15048
    setTimeout(function() {
        window.URL.revokeObjectURL(url);
    }, 1000);
}

function AddZero (HasilKompresi,sisa) {
   for (var i = 0; i < sisa; i++) {
     HasilKompresi+='0';
   }
   return HasilKompresi;
}

function DeleteZero (HasilKompresi,sisa) {
  console.log(HasilKompresi.length);
  Hasil = HasilKompresi.substr(0, HasilKompresi.length - sisa);
  console.log(Hasil.length);
  return Hasil;
}

function text2Binary (input) {
  var output = '';
   for (i=0; i < input.length; i++) {var e=input[i].charCodeAt(0);var s = "";
    do{
        var a =e%2;
        e=(e-a)/2;
        s=a+s;
        }while(e!=0);
        while(s.length<8){s="0"+s;}
        output+=s;
    }
    return output;
}

// add bit
function AddBit(binary) {
  length = binary.length;
  console.log(length);
}

 //convert binary to whitespace
 function binarytowhitespace(a){
  var data = a.split('');
  for (var i = 0; i < a.length; i++) {
    if(data[i] == '0'){
      data[i] = '\n';
    }
    else{
      data[i] = '\t';
    }
  }
  var a = data.join('');
  return a;
}

 //convert whitespace to binary
 function whitespacetobinary(a){
  var data = a.split('');
  console.log(data);
  for (var i = 0; i < a.length; i++) {
    if(data[i] == '\n'){
      data[i] = '0';
    }
    else{
      data[i] = '1';
    }
  }
  var a = data.join('');
  return a;
}

 //convert binary to text
 function binarytotext(str) {
  // console.log(str.length);
  // Removes the spaces from the binary string
    str = str.replace(/\s+/g, '');
    // Pretty (correct) print binary (add a space every 8 characters)
    str = str.match(/.{1,8}/g).join(" ");
    console.log(str);

    var newBinary = str.split(" ");
    var binaryCode = [];

    for (i = 0; i < newBinary.length; i++) {
        binaryCode.push(String.fromCharCode(parseInt(newBinary[i], 2)));
    }
    
    console.log(binaryCode);
    return binaryCode.join("");


  // var binString = '';

  // str.split(' ').map(function(bin) {
  //     binString += String.fromCharCode(parseInt(bin, 2));
  //   });
  // return binString;
}

 //detect separate character position
 function detect(data,data2){
  index = data.indexOf(data2);
  
  return index+8;
  console.log(index);

  // var split = data.split('');
  // console.log(split);
  // var separate = data2.split('');
  // console.log(separate);
  // for (var i = 0; i < split.length; i++) {
  //   // j = i;
  //   if (split[i] == separate[0]){
  //     i++
  //     if (split[i] == separate[1]){
  //       i++
  //       if (split[i] == separate[2]){
  //         i++
  //         if (split[i] == separate[3]){
  //           i++
  //           if (split[i] == separate[4]){
  //             i++
  //             if (split[i] == separate[5]){
  //               i++
  //               if (split[i] == separate[6]){
  //                 i++
  //                 if (split[i] == separate[7]){
  //                   i++
  //                   return i;
  //                   break;
  //                 }
  //               }
  //               else {
  //                 i--;
  //               }
  //             }
  //             else {
  //               i--;
  //             }
  //           }
  //           else {
  //             i--;
  //           }
  //         }
  //         else {
  //           i--;
  //         }
  //       }
  //       else {
  //         i--;
  //       }
  //     }
  //     else {
  //       i--;
  //     }
  //   }
  //   else {
  //     i--;
  //   }
  // }
}

//convert input text to a byte array
function textToArray($text)
{
  $ar = array_slice(unpack("C*", "\0".$text), 1);
  return $ar;
    // console.log('')
  }

// get frecuency
function Frequency(string) {
  var freq = {};
  for (var i=0; i<string.length;i++) {
    var character = string.charAt(i);
    if (freq[character]) {
      freq[character]++;
    } else {
      freq[character] = 1;
    }
  }
  return freq;
};

// sort object from high frecuency
function SortByFreq(a,b) {
  if (a[0] === b[0]) {
          return 0;
      }
      else {
          return (a[0] > b[0]) ? -1 : 1;
      }
}

function object2array2d(freqs){
  var letters = [];
  for (var ch in freqs){
    letters.push([freqs[ch],ch]);
  }
  return letters;
}

function object2array(TableSF) {
var sortable = [];
for (var vehicle in TableSF) {
    sortable.push([vehicle, TableSF[vehicle]]);
}

sortable.sort(function(a, b) {
    return b[1] - a[1];
  });
}

//Tabel SF
var bit = {};
function tableSF(tuples)
{
	var total=0;
	for(var i in tuples){
		total += tuples[i][0];
	}

	var result = 0;
	for (var i = 0; i < tuples.length; i++) {
		result+=tuples[i][0];
		if (result >= total-result) {
			var point = i+1;
			break;
		}	  	
	}

	var up=[],
	down=[];
	for (var i = 0; i < point; i++) {
		up.push(tuples[i]);
		if(bit[tuples[i][1]] == undefined) {
			bit[tuples[i][1]] = '0';
		}
		else {
			bit[tuples[i][1]] += '0';
		}
	}

	for (var i = point; i < tuples.length; i++) {
		down.push(tuples[i]);
		if(bit[tuples[i][1]] == undefined) {
			bit[tuples[i][1]] = '1';
		}
		else {
			bit[tuples[i][1]] += '1';
		}
	}


	if(up.length>1){tableSF(up)}
		if(down.length>1){tableSF(down)}

			return bit;
	}

	function encode(text,bit) {
		output = '';
		for(s in text)
			output+=bit[text[s]];
		return output;
	}
	function decode(tableSF,encoded)  
	{ 
		var start = 0,
   output = '';

   for(var i = 1; i <= encoded.length; i++) {
     substring = encoded.substring(start,i);
     var test = getKeyByValue(tableSF, substring)
     if (test != undefined) {
      start = i;
      output += test;

    }
  }
  return output;
}  

function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}

// function sortOBJ(object){
//   var sortable = [];
//   for (var vehicle in object) {
//       sortable.push([vehicle, object[vehicle]]);
//   }

//   sortable.sort(function(a, b) {
//       return a[1] - b[1];
// }

function createObject(array) {
  var obj = {};
  for (var i = 0; i < array.length; i++) {
    obj[array[i][0]] = array[i][1];
  }
  return obj;
}

function decode2(tableSF,encoded) {
      var start = 0,
   output = '';

   for(var i = 1; i <= encoded.length; i++) {
     substring = encoded.substring(start,i);
     var test = stringMatch(tableSF, substring)
     if (test != undefined) {
      start = i;
      output += test;

    }
  }
  return output;
} 

function stringMatch (tableSF,substring) {
  for (var i = 0; i < tableSF.length; i++) {
    if (tableSF[i][1] == substring) {
      // console.log(tableSF[i][1]);
      return tableSF[i][0];
    }
  }
}

  // function header (tuples) {

  // }

  function StringToArray2D (a) {
    var result = [];

    a = a.split(','); 

    while(a[0]) {
      result.push(a.splice(0,2));
    }

    return result;
  }

  // testing
  test = '123456781234';
  console.log(test);
  length = test.length;
    console.log(length);

  modulus = test.length%8;
    console.log(modulus);
    sisa = 8-modulus;
      console.log(sisa);

test2 = AddZero(test,sisa);
  console.log(test2);

test3 = DeleteZero (test2,sisa);
  console.log(test3);

