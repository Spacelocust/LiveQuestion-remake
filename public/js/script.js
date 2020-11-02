
function onclickBtnLike(e){
    e.preventDefault()

    const url = this.href;
    const spanCount = this.querySelector('span.js-likes');
    const icon = this.querySelector('i')
    let httpRequest = new XMLHttpRequest()

    httpRequest.onreadystatechange = function(){
        if(httpRequest.readyState === 4){
            if(httpRequest.status === 200){
                let likeData = JSON.parse(httpRequest.responseText);
                let likeDataTb = Object.keys(likeData)
                spanCount.textContent = likeData[likeDataTb[2]]
                if(icon.classList.contains('fas')){
                    icon.classList.replace('fas','far')
                }else{
                    icon.classList.replace('far','fas')
                }
            }else{
                alert('impossible de contacter le serveur')
            }
        }
    }
    httpRequest.open('GET',url, true)
    httpRequest.send()
}

document.querySelectorAll('a.js-like-link').forEach(function (link){
    link.addEventListener('click', onclickBtnLike);

});