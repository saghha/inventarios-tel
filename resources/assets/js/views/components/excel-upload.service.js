import * as axios from 'axios';

const BASE_URL = 'http://cap.local';

function upload(formData) {
    const url = `${BASE_URL}/excel/upload`;
    return axios.post(url, formData)
        // get data
        .then(function(x) { 
            console.log(x);
            return x.data});
        // add url field
        //.then(x => x.map(img => Object.assign({},
        //    img, { url: `${BASE_URL}/storage/images/${img.url}` })));
}

export { upload }