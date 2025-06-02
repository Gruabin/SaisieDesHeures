function verifChampOrdre(evt){
    if(evt.target.value.length === 2 && evt.target.value.toUpperCase() === labelOrdre.toUpperCase()){
        evt.target.value = "";
    }
    if(evt.target.value.slice(0, 2).toUpperCase() === labelOrdre.toUpperCase()){
        evt.target.value = evt.target.value.slice(2);
    }
    if(evt.target.value.length > 7){
        evt.target.value = evt.target.value.slice(0, -1);
    }
}