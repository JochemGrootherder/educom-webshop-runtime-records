<script>
    document.getElementById("AddArtist").addEventListener('click', function(e)
    {
        alert("akjfdkajlfd");
    })

function AddArtist()
{
    alert("yeet");
    html='\
        <div class="form-group">\
            <label class="control-label">Artist</label>\
            <input type="text" class="form-control" name="artist" placeholder= "artist" value=""></input>\
        </div>'

    var form=document.getElementById("AddItem")
    form.innerHTML+=html;
}
</script>