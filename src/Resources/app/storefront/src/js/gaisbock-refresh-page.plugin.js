import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbockRefreshPage extends Plugin {
    init(){
        const getButton = document.getElementById('gaisbock-submit');
        getButton.addEventListener('click',function(){
            location.reload();
        });
    }
}