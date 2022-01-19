console.log('toto');

function onClickLike(event) {
    event.preventDefault();
    const url = this.href;
    const spanCount = this.querySelector('span.js-likes');
    const icone = this.querySelector('i');
    axios.get(url).then(function (response) {
        spanCount.textContent = response.data.likes;
        if (icone.classList.contains('bi-hand-thumbs-up-fill')) {
            icone.classList.replace('bi-hand-thumbs-up-fill', 'bi-hand-thumbs-up')
        } else {
            icone.classList.replace('bi-hand-thumbs-up', 'bi-hand-thumbs-up-fill')
        }
    }).catch(function (error) {
        if (error.response.status === 403) {
            window.alert("Vous devez vous connecter")
        } else {
            window.alert("Une erreur s'est produite")
        }
    })
}
document.querySelectorAll('a.js-likes').forEach(function (link) {
    link.addEventListener('click', onClickLike);
})