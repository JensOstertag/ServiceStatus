import * as Modal from "../Modal.js";
import { t } from "../Translator.js";
import * as ButtonLoad from "../ButtonLoad.js";

export const init = async () => {
    Modal.init();

    const translations = await Promise.all([
        t("Delete service"),
        t("Do you really want to delete this service?"),
        t("Delete")
    ]);

    const deleteService = document.querySelector("#delete-service");
    if(deleteService !== null) {
        deleteService.addEventListener("click", () => {
            Modal.open({
                title: translations[0],
                text: translations[1],
                confirm: translations[2]
            }, (confirm) => {
                if(confirm) {
                    ButtonLoad.load(deleteService);
                    window.location.href = deleteService.getAttribute("data-delete-href");
                }
            });
        });
    }
}

export default { init };
