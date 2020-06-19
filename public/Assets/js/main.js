function pswValidation() { 
    var res; 
    var str = 

    document.getElementById("psw").value;

    if (str.match(/[a-z]/g) && str.match(/[A-Z]/g) && str.match(/[0-9]/g) && str.match(/[^a-zA-Z\d]/g) && str.length >= 8){ 
        res = "TRUE";
        document.getElementById("submit").type="submit";
        document.getElementById("signup").submit();
    }else{ 
        res = "FALSE";
        alert("The entered password is wrong, it must contain: At least 1 uppercase character.At least 1 lowercase character.At least 1 digit.At least 1 special character.Minimum 8 characters.")
    }
}