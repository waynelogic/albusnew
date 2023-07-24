import {Modal} from "./modal";

export default function init(element) {
    console.log(element);
    const modalId = element.dataset.modalAjax;
    const request = element.dataset.modalRequest;
    const partial = element.dataset.modalPartial;
    if (modalId === undefined || request === undefined || partial === undefined) {
        return console.log('Вы не настроили кнопку модального окна');
    }

    element.addEventListener('click', () => {
        const onAfterShow = () => {
            oc.ajax(request, {
                update: { [partial]: `[data-modal-content="${modalId}"]` }
            })
        }
        const modalOptions = {afterShown: onAfterShow}
        new Modal(modalId, modalOptions);
    })
}