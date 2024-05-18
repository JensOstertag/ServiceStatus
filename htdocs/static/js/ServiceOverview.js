export const ServiceOverview = {
    init: () => {
        $(".status-history-element").each((index, element) => {
            tippy(element, {
                allowHTML: true,
                content: $(element).find(".tooltip")[0].innerHTML,
                placement: "bottom"
            });
        });
    }
};

export default ServiceOverview;
