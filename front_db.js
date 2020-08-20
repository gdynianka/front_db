// to jest plik bliblioteki FRONT_DB - nie edytować


function Usun(tabelka,id,reload=true){
xhttp = new XMLHttpRequest();
xhttp.open("POST", "front_db.php?action=usun", true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhttp.send('tabelka='+tabelka+'&id='+id);
if (reload) location.reload();
}

function Zapisz(tabelka,goto=''){

const id = new URLSearchParams(window.location.search).get('id');


event.preventDefault();

inputs = Array.from(event.target.elements).filter(e => e.getAttribute("name"))

arr = ['tabelka='+tabelka];
if (id) arr.push('id='+id);
for (i in inputs) arr.push(inputs[i].name + '=' + inputs[i].value);

xhttp = new XMLHttpRequest();
xhttp.open("POST", "front_db.php?action=zapisz", true);
xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xhttp.send(arr.join('&'));

if (goto=='') location.reload(); else location.href = goto;

}





function ogonki(word){
var a1 = ['ę','ó','ą','ś','ł','ż','ź','ć','ń'];
var a2 = ['Ę','Ó','Ą','Ś','Ł','Ż','Ź','Ć','Ń'];
var a3 = ['e','o','a','s','l','z','z','c','n'];
for (i in a1) word = word.replace(a1[i],a3[i]);
for (i in a2) word = word.replace(a2[i],a3[i]);

for (i in a1) word = word.split(a1[i]).join(a3[i]);
for (i in a2) word = word.split(a2[i]).join(a3[i]);

return word;
}

template = '';


function Pobierz(tabelka,id){


    try { filter = ogonki(event.target.value); } catch (error) { filter = '' }
    try { pole   = event.target.name;  }         catch (error) { pole   = '' }


    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
           if (xmlhttp.status == 200)
               PokazListe(JSON.parse(xmlhttp.responseText),id);
           else if (xmlhttp.status == 400) alert('There was an error 400');
           else alert('something else other than 200 was returned');

        }
    };

    xmlhttp.open("POST", "front_db.php?action=pobierz&tabelka="+tabelka, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send('pole='+pole+'&filter='+filter);

}



function PokazListe(result,id){

if (template=='') template = document.getElementById(id).innerHTML;
document.getElementById('lista').innerHTML = '';

for (i in result) {
                  str = template;
                  keys   = Object.keys(result[i]);
                  values = Object.values(result[i]);
                  for (k=0;k<=keys.length;k++) str = str.split('['+keys[k]+']').join(values[k]);
                  document.getElementById('lista').innerHTML += str;
                  }

}





function Wczytaj(tabelka){


    const id = new URLSearchParams(window.location.search).get('id');
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
           if (xmlhttp.status == 200)
               WpiszPola(JSON.parse(xmlhttp.responseText));
           else if (xmlhttp.status == 400) alert('There was an error 400');
           else alert('something else other than 200 was returned');

        }
    };

    xmlhttp.open("GET", "front_db.php?action=pobierz&tabelka="+tabelka+'&id='+id, true);
    xmlhttp.send();

}


function WpiszPola(result){

keys   = Object.keys(result);
values = Object.values(result);

for (k=0;k<=keys.length;k++){
     element = document.getElementById(keys[k]);
     if (element) element.value = values[k];
     }


}


