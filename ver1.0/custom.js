//styling JS
$(document).ready(function(){
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
});
  function myFunction(x) {
    x.classList.toggle("change");
    $(".tabBlock").toggle('slow');
  }

// convert string to binary
function text2Binary(string) {
  var length = string.length,
  output = [];
  for (var i = 0;i < length; i++) {
    var bin = string[i].charCodeAt().toString(2);
    output.push(Array(8-bin.length+1).join("0") + bin);
  } 
  return output.join("");
}
   // console.log('text2binary='+text2Binary('abc'));

 //convert binary to whitespace
 function binarytowhitespace(a){
  var data = a.split('');
  for (var i = 0; i < a.length; i++) {
    if(data[i] == '0'){
      data[i] = ' ';
    }
    else{
      data[i] = '\t';
    }
  }
  var a = data.join('');
  return a;
}

 //convert binary to text
 function binarytotext(binary) {
  return binary.replace(/[01]{8}/g, function (v) {
    return String.fromCharCode(parseInt(v, 2));
  });
}

 //convert whitespace to binary
 function whitespacetobinary(a){
  var data = a.split('');
  for (var i = 0; i < a.length; i++) {
    if(data[i] == ' '){
      data[i] = '0';
    }
    else{
      data[i] = '1';
    }
  }
  var a = data.join('');
  return a;
}

 //detect separate character position
 function detect(data,data2){
  var split = data.split('');
  var separate = data2.split('');
  for (var i = 0; i < split.length; i++) {
    if (split[i] == separate[0]){
      i++
      if (split[i] == separate[1]){
        i++
        if (split[i] == separate[2]){
          i++
          if (split[i] == separate[3]){
            i++
            if (split[i] == separate[4]){
              i++
              if (split[i] == separate[5]){
                i++
                if (split[i] == separate[6]){
                  i++
                  if (split[i] == separate[7]){
                    i++
                    return i;
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
  
// function for shannon fano compression
// Compress text, returns a byte array
//
function compressText($text)
{
    //get the text as a byte array
    $src_bin = textToArray($text);
    
  // return compressBin($src_bin);
  // console.log('textToArray : '+src_bin)
}

//convert input text to a byte array
function textToArray($text)
{
  $ar = array_slice(unpack("C*", "\0".$text), 1);
  return $ar;
    // console.log('')
  }

// get frecuency
function getFrequency(string) {
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
function sortAssocObject(list) {
  var sortable = [];
  for (var key in list) {
    sortable.push([key, list[key]]);
  }

  sortable.sort(function(a, b) {
    return (a[1] > b[1] ? -1 : (a[1] < b[1] ? 1 : 0));
  });

  var orderedList = {};
  for (var i = 0; i < sortable.length; i++) {
    orderedList[sortable[i][0]] = sortable[i][1];
  }

  return orderedList;
}

function frequency(str){
  var freqs={};
  for (var i in str){

   if(freqs[str[i]]==undefined){

    freqs[str[i]]=1;
  }
  else {
    freqs[str[i]]=freqs[str[i]]+1;
  }
}

return freqs;
}

function sortfreq(freqs){
  var tuples=[];
  for( var let in freqs){
   tuples.push([freqs[let],let]);
 }
 tuples.sort();
 return tuples.reverse();
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

   for(var i = 1; i < encoded.length+1; i++) {
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


//Source
var text = 'abc 		       		   	  		  	   		  		',
frequency = frequency(text),
sort = sortfreq(frequency),
tableSF = tableSF(sort);
console.log('text : '+text);
console.log(sort);
console.log(tableSF);
encode = encode(text,tableSF);
decode = decode(tableSF,encode);
console.log(encode);
console.log(decode);