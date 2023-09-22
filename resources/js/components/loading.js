// ---- -- - ------ --- ------- ---------
// This Is A Method For Loading Animation
// ---- -- - ------ --- ------- ---------

function loading(status) {

    const loading = $("section#loading");
    if (status) {
        loading.css({
            display: 'grid'
        })
    } else {
        loading.css({
            display: 'none'
        })
    }

}

export default loading;