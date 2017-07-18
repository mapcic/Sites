var albms=API.photos.getAlbums({"owner_id":-33809555});
var albmsId=albms.items@.id;
if (albms.count > 24) {
	albms.count = 24;
}
var photos = [];
var i = 0;
while( i < albms.count ){
	var resp = API.photos.get({"owner_id":-33809555, "album_id":albmsId[i], "count": 30});
	photos.push(resp.items);
	i = i + 1;
}
return photos;