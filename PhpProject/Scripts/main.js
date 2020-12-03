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

function confirmDelete(albumTitle, albumId){
    if(confirm("Are you sure you wish to delete '" + albumTitle + "'? All of its photos and their comments will be deletely permanently.")){
        $('#deleteAlbumIdInput').val(albumId);
        $('#myAlbumsForm').submit();
    }
}

// Changes the album visualized according to dropDownList change on FriendPictures.php and MyPictures.php
$('#friendAlbum').change(function() {
    var id = this.options[this.selectedIndex].value;
    $('#albumId').val(id);
    $(this).closest('form').submit();
});

// Changes the picture visualized according to thumbnail clicked on FriendPictures.php and MyPictures.php
$('.thumbnail-item').each(function() {
    $(this).click(function() {
        var pictureId = $(this).attr("id");
        $('#pictureId').val(pictureId);
        $(this).closest('form').submit();
        console.log(id);
    })
});

// Confirm that new comment will be added to picture
$("#addComment").click(function() {
    $("#newCommentAdded").val("1");
});