export default class ClsAsync {

    constructor(name) {
        this.name = name;
    }

    async getAjaxAsync(url, method, data) {
        const result = await
        $.ajax({
          url: url,
          type: method,
          data: data
        });
        return result;
    }
    getName(){
        console.log(`Hello, ${this.name}!`);
    }

    async  BtnLoading(elem, text) {
        $(elem).attr("data-original-text", $(elem).html());
        $(elem).prop("disabled", true);
        $(elem).html('<i class="spinner-border spinner-border-sm"></i> '+text+'...');
    }
    
    async BtnReset(elem, text) {
        $(elem).prop("disabled", false);
        $(elem).html($(elem).attr("data-original-text"));

    }

  
}