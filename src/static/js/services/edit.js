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

    // Setup listeners for monitoring setting checkboxes
    const setupCheckbox = (monitoringType) => {
        const handler = () => {
            const checked = document.querySelector("#monitoring-" + monitoringType + "-enabled").checked;
            if(checked) {
                document.querySelector("#monitoring-" + monitoringType + "-details").classList.remove("hidden");
                document.querySelectorAll("#monitoring-" + monitoringType + "-details input").forEach((input) => {
                    input.setAttribute("required", "");
                });
            } else {
                document.querySelector("#monitoring-" + monitoringType + "-details").classList.add("hidden");
                document.querySelectorAll("#monitoring-" + monitoringType + "-details input").forEach((input) => {
                    input.removeAttribute("required");
                });
            }
        }

        document.querySelector("#monitoring-" + monitoringType + "-enabled").addEventListener("change", () => {
            handler();
        });

        handler();
    }
    setupCheckbox("http");
    setupCheckbox("ping");
}

export default { init };
