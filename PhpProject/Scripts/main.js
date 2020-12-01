// Confirms with user if they would like to make an album with no description.
$("#submitCreateAlbumButton").click(function(){
    let title = $("#albumTitle").val();
    if(title.trim() !== ""){
        let description = $("#albumDescription").val();
        if(description.trim() === ""){
            if(confirm("You haven't entered an album description. Are you sure you wish to create this album?")){
                this.form.submit();
            }
        } else{
            this.form.submit();
        }
    } else {
        this.form.submit();
    }
});