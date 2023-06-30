export default function initTabs($root) {
    const $buttons = $root.querySelectorAll('.tab-button');
    $buttons.forEach(button => {
        button.onclick = (button) => { showtab(button.target) };
    });
    const $tabs = $root.querySelectorAll('.tab-content');

    // let $hash = window.location.hash;
    // if ($hash != undefined) {

    // }
    // console.log(window.location.hash);

    function showtab($button) {
        if ($button.dataset.tab != undefined) {
            let $elToShow = $root.querySelector('#' + $button.dataset.tab);
            hideAll();
            $elToShow.classList.remove("hidden");
            $button.classList.add("active")
        }
    }
    function hideAll() {
        $buttons.forEach(element => { element.classList.remove("active"); });
        $tabs.forEach(element => { element.classList.add("hidden"); });
    }
}