// TUTORIEL :
//
// Commande de base :
// new pagination("NOMDELAPAGINATION", "MAX D'ELEMENT QUE TU VEUX AFFICHÉ", "MAX DE BOUTON QUE TU VEUX AFFICHÉ (0 POUR ILLIMITÉ)", "table-row");
//
// Requis :
// paginationView="NOMDELAPAGINATION" pour dire où est le parent content dynamique
// class="contentPagination" dans le paginationView pour dire où est le content dynamique
// paginationName="NOMDELAPAGINATION" sur les items pour les identifiés
// paginationId="{{ loop.index }}" sur les items afin d'avoir un id de chaque item
// paginationBtn="NOMDELAPAGINATION" pour dire où sont les boutons
// class="btnPagination" pour mettre les boutons à précisement ici afin de placer entre precedent et suivant si il y en a

class pagination {
    constructor(name, displayMax, displayBtnMax, option) {
        this.name = name
        this.displayMax = displayMax
        this.displayBtnMax = displayBtnMax
        if (this.displayBtnMax == 0) { this.displayBtnMax = 0 }
        this.option = option
        this.displayCount = 0
        this.tested = ""
        this.nombreObjet = $('[paginationName="' + name + '"]').length
        this.nombrePagination = this.nombreObjet / this.displayMax
        this.paginationInit()
    }
    paginationInit() {
        if (this.nombreObjet > this.displayMax) {
            if (this.nombrePagination < this.displayBtnMax) {
                this.displayBtnMax = this.nombreObjet / this.displayMax
            }
            for (let display = 1; display < Math.ceil(this.displayBtnMax) + 1; display++) {
                if (display == 1) { this.varBonus = "active" } else { this.varBonus = null }
                $('[paginationBtn="' + this.name + '"] .btnPagination').append('<li btnPaginationId="' + display + '" class="page-item cursor-pointer ' + this.varBonus + '"><a class="page-link">' + display + '</a></li>');
                this.btnPagination = document.querySelector('[btnPaginationId="' + display + '"]');
                this.lastBtnPagination = display
                this.btn(this.btnPagination)
            }
            $('[paginationName="' + this.name + '"]').css('display', 'none');
            for (this.displayCount; this.displayCount < this.displayMax + 1; this.displayCount++) {
                $('[paginationId="' + this.displayCount + '"]').css('display', this.option);
            }
        }
    }
    btn(btnPagination) {
        btnPagination.addEventListener("click", () => {
            var btnPaginationId = $(btnPagination).attr('btnPaginationId')
            if (btnPaginationId == 1) {
                this.displayCount = 0;
            } else if (btnPaginationId == 2) {
                this.displayCount = this.displayMax;
            } else if ((btnPaginationId * this.displayMax) > this.nombreObjet) {
                this.displayCount = this.nombreObjet - 1
            } else {
                this.displayCount = this.displayMax * this.displayMax;
            }
            $('[paginationView="' + this.name + '"]').load("?view=" + this.displayCount + '&maxView=' + this.displayMax + ' [paginationView="' + this.name + '"] .contentPagination', function () {
                this.displayCount = this.displayCount + this.displayMax
                $('[btnPaginationId').removeClass("active")
                $('[btnPaginationId="' + btnPaginationId + '"').addClass("active")
            });
        })
    }
}