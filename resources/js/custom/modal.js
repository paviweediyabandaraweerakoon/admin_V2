ModalOptions = {
    toggleModal: function (modal) {
        modal = '#' + modal;
        $(modal).modal('toggle');
    },
    showModal: function (modal) {
        modal = '#' + modal;
        $(modal).modal('show');
    },
    hideModal: function (modal) {
        modal = '#' + modal;
        $(modal).modal('hide');
    }
};
