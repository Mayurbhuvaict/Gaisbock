import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockRefreshPage extends Plugin {
    init(){
        const getButton = document.getElementById('gaisbock-submit');
        getButton.addEventListener('click',function(){
            // window.location.replace(window.location.href);
            let title = document.getElementById('reviewTitle');
            if (title.value.length != 0){
                setTimeout(function(){
                    location.reload();
                }, 1000);
            }
        });
    }
}