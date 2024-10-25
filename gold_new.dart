const request = {
  "invoice": {
    "shop_id": 1,
    "manufacture_cost_gram": 100,
    "customer_name": "Eslam Kamel",
    "cutomer_phone": "01024510803",
  },
  "orders": [
    {
      "product_id": 1,
      "unit_price": 400,
      "description": "some description",
    },
    {
      "product_id": 2,
      "unit_price": 200,
      "description": "some description",
    },
  ],
};

/*
! user_id will be fetched from the token
! quantity will be calculated by the backend

 */

var fdf = {
  "shop_id": 1,
  "manufacture_cost_gram": 4100,
  "customer_name": "Eslam Ahmed Kamel",
  "customer_phone": "01020504470",
  "total_price": 4000,
  "user_id": 1,
  "quantity": 2
};
var dfdf = [
  {"product_id": 1, "description": "some description", "unit_price": 2000},
  {"product_id": 2, "description": "some description", "unit_price": 2000}
];
