function validate(){

    var user = document.getElementById('username').value;
    var pass = document.getElementById('password').value;
    var repeat = document.getElementById('repeat').value;
    var email = document.getElementById('email').value;
    
    var userCheck = user.match(/^[a-zA-Z0-9]{8,16}$/);    
    
    var passCheck1 = pass.match(/^[a-zA-Z0-9]{8,16}$/);    
    var passCheck2 = pass.match(/(^.*[A-Z].*$)/);
    var passCheck3 = pass.match(/(^.*[0-9].*$)/);
    
    var passCheck = passCheck1 && passCheck2 && passCheck3;
    
    var sameCheck = (pass == repeat);
    
    var emailCheck = email.match(/^[a-zA-Z0-9_]{0,128}.@uni-sofia.bg$/);
    
    var valid = (userCheck && passCheck && emailCheck && sameCheck);
    
    if(valid){
        return true;
    }
    
    else{
        document.getElementById('err').innerHTML=''; 
        if (!userCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Потребителското име трябва да е от латински букви и цифри и с дължина от 8 до 16 символа!<br/>";
            document.getElementById('err').style.display='block';   
        }
        if (!passCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Паролата трябва да е от латински букви и цифри и с дължина от 8 до 16 символа и да има главна буква и цифра!<br/>";
            document.getElementById('err').style.display='block';    
        }
        if (!emailCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Електронната поща трябва да е от суси (example@uni-sofia.bg)!<br/>";
            document.getElementById('err').style.display='block';  
        }
        if (!sameCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Паролата и повторението и не съвпадат!<br/>";
            document.getElementById('err').style.display='block';  
        }
    }
    
    return false;    
}


function validateChange(){

    var newpass = document.getElementById('new').value;
    var oldpass = document.getElementById('old').value;
    var repeat = document.getElementById('repeat').value;   
    
    var passCheck1 = newpass.match(/^[a-zA-Z0-9]{8,16}$/);    
    var passCheck2 = newpass.match(/(^.*[A-Z].*$)/);
    var passCheck3 = newpass.match(/(^.*[0-9].*$)/);
    
    var passCheck = passCheck1 && passCheck2 && passCheck3;
    
    var sameCheck = (newpass == repeat);
    
    var newCheck = (oldpass != newpass);
    
    var valid = sameCheck && passCheck && newCheck;
    
    if(valid){
        return true;
    }
    else{
        document.getElementById('err').innerHTML=''; 
        if (!passCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Новата парола трябва да е от латински букви и цифри и с дължина от 8 до 16 символа и да има главна буква и цифра!<br/>";
            document.getElementById('err').style.display='block';    
        }
        if (!sameCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Новата парола и повторението и не съвпадат!<br/>";
            document.getElementById('err').style.display='block';  
        }
        if (!newCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Старата парола и новата не може да съвпадат!<br/>";
            document.getElementById('err').style.display='block';  
        }
        
    }
    
    return false;    
}


function validateEmail(){
    var email = document.getElementById('email').value;
    
    var emailCheck = email.match(/^[a-zA-Z0-9_]{0,128}.@uni-sofia.bg$/);
  
    if(emailCheck){
        return true;
    }
    
    document.getElementById('err').innerHTML=''; 
    document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Електронната поща трябва да е от суси (example@uni-sofia.bg)!!<br/>";
    document.getElementById('err').style.display='block';
    return false;
}


function validateDates(){
    var from = document.getElementById('from').value;
    var to = document.getElementById('to').value;
    
    var dt1   = (from.substring(8,10));
    var mon1  = (from.substring(5,7));
    var yr1   = (from.substring(0,4));
    var datefrom = new Date(yr1, mon1-1, dt1);
     
    var dt2   = (to.substring(8,10));
    var mon2  = (to.substring(5,7));
    var yr2   = (to.substring(0,4));
    var dateto = new Date(yr2, mon2-1, dt2);

    var res = (datefrom > dateto);
    
    
    if (res==true){
        document.getElementById('err').innerHTML=''; 
        document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Началната дата не може да е след крайната!";
        document.getElementById('err').style.display='block';
        return false;
    }
    
    return true;
    
}


function validateElective(){
    var elective = document.getElementById('electiveName').value;
    var lecturer = document.getElementById('lecturerName').value;
    
    var electiveCheck = elective.match(/^[a-zA-Z0-9а-яА-Я.\s-]{5,24}$/);
    var lecturerCheck = lecturer.match(/^[a-zA-Z0-9а-яА-Я.\s-]{5,24}$/);
    
    var valid = (electiveCheck && lecturerCheck);
    
    if(valid){
        return true;
    }
    
    else{
        document.getElementById('err').innerHTML=''; 
        if (!lecturerCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Името на преподавателя трябва да е от 5 до 24 символа (латински, кирилица, тире, точка и интервал)!<br/>";
            document.getElementById('err').style.display='block';    
        }
        
        if (!electiveCheck){
            document.getElementById('err').innerHTML = document.getElementById('err').innerHTML + "Името на изборната дисциплина трябва да е от 5 до 24 символа (латински, кирилица, тире, точка и интервал)!<br/>";
            document.getElementById('err').style.display='block';  
        }      
    }
    
    return false;
    
}