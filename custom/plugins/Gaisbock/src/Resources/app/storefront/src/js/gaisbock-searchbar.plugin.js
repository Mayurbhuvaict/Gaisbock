import Plugin from 'src/plugin-system/plugin.class';

export default class gaisbcksearchbar extends Plugin{
    init(){
        const parentDiv = document.querySelector('.header-main');
        const searchButton = document.querySelector('.gaisbock-search-button-for-css');
        searchButton.addEventListener('click',()=>{
            if(parentDiv.classList.contains('hovered-header') === false){
                parentDiv.classList.add('hovered-header');
            }else{
                parentDiv.classList.remove('hovered-header');
            }
        });
        const closeButton = document.querySelector('.gaisbock-search-button-for-css-close');

        closeButton.addEventListener('click',()=>{
            parentDiv.classList.remove('hovered-header');
        });

        const linkElement = document.querySelector('div[domain]');

        if (linkElement) {
            const domainValue = linkElement.getAttribute('domain');
            if(domainValue === 'frontend.search.page'){
                parentDiv.classList.add('hovered-header');
            }else{
                parentDiv.classList.remove('hovered-header');
            }
        }
    }
}