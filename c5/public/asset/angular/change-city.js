class angModelCity {
  constructor(data) {
    this.data = data;
  }

  create() {
    if (!this.data.id) {
      throw new Error('City not found');
    }
    return this;
  }

  getArea() {
    return this.data.area;
  }

  getId() {
    return this.data.id;
  }
}

class angComponentChangeCity {
  constructor($scope, $http) {
    this.$scope = $scope;
    this.$http = $http;

    /**
     * Выбранный регион
     */
    this.area = 0;

    /**
     * Список городов в регионе
     */
    this.list = [];

    this.areas = [];
  }

  $onInit() {
    if (this.city) {
      this.$http({
        url: '/asset/angular/areas.json',
      })
        .then(json => {
          this.areas = json.data;
          return true;
        })
        .then(() => this.run())
        .catch((exc) => {
          console.log(exc);
        });
    }
  }

  run() {
    return this.$http({
      url: `/ajax/get-city/${this.city}`,
    })
      .then(r => (new angModelCity(r.data)).create())
      .then(c => {
        this.area = c.getArea();
        this.city = c.getId();
        return c;
      })
      .then(() => this.changeArea())
      .catch((exc) => {
        console.log(exc);
        alert('Что-то пошло не так');
      });
  }

  changeArea() {
    return this.$http({
      url: `/ajax/user_company_region/${this.area}`,
    })
      .then(json => {
        this.list = json.data;
        return this.list;
      });
  }

  isSelected(v1, v2) {
    return parseInt(v1, 10) == parseInt(v2, 10);
  }
}
