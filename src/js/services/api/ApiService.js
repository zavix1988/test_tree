import $ from 'jquery';

class ApiService {

    getResource(url, data) {
         return $.ajax({
            type: 'GET',
            url: this.getApiPrefix() + url,
            data: data
        });
    }

    postResource(url, params) {
        return $.ajax({
            type: 'POST',
            url: this.getApiPrefix() + url,
            data: JSON.stringify(params)
        });
    }

    putResource(url, params) {
        return $.ajax({
            type: 'PUT',
            url: this.getApiPrefix() + url,
            data: JSON.stringify(params)
        });
    }

    deleteResource(url) {
        return $.ajax({
            type: 'DELETE',
            url: this.getApiPrefix() + url,
        });
    }

    getApiPrefix() {
        return window.location.protocol + "//" + window.location.host + "/api";
    }
}

export default ApiService;