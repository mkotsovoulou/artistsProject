 x=3;
if (x==5) {
console.log("Equal");
}
else {
    console.log("Not equal");
}
alert("ERRORR");
name = prompt("What is your name?");
document.write("<h2> Welcome: " + name + "</h2>");

if (name=="Maira") {
   document.getElementById("message").innerHTML="xaxa";
   document.getElementById("message").style.color= "blue";
}

function doSomething(){
   document.getElementById("message").style.backgroundColor="red";
}