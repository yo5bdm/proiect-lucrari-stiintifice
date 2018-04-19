/* 
 * Copyright Erdei Rudolf (www.erdeirudolf.com) - All rights reserved.
 * Code available under the GPL V2 license terms and conditions
 */
var formatNume = [
    'Popescu Ion',
    'Popescu I.',
    'I. Popescu'
];
//php ucfirst equivalent.
function ucfirst(string) {
    return string[0].toUpperCase() + string.substring(1);
}
//returns the capitalised first letter of the name plus a dot John => J.
function prescurtarePrenume(string) {
    return string[0].toUpperCase() + ".";
}
//formats the name according to the desired format
function afiseazaNume(nume, prenume, format) {
    switch(Number(format)){
        case 0:
            return ucfirst(nume) + " " + ucfirst(prenume);
        case 1:
            return ucfirst(nume) + " " + prescurtarePrenume(prenume);
        case 2:
            return prescurtarePrenume(prenume) + " " + ucfirst(nume);
        default:
            return ucfirst(nume) + " " + ucfirst(prenume);
    }
    
}
