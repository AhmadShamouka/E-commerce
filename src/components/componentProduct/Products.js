import axios from "axios";
import React, { useEffect, useState } from "react";

function Products(props) {
  const header =
    "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjo0MCwibmFtZSI6IlNlbGxlciIsInJvbGVuYW1lIjoiYSIsImV4cCI6MTcwMTk4MTk3M30.gGTNVhf40a0dD8ngmbd7r8kXJrQjlVa93l7lWD_ieK4";
  const [formData, setFormData] = useState({});
  useEffect(() => {
    get();
  }, []);
  const get = () => {
    try {
      const response = axios.get(
        "http://localhost/e-commerce/backend/api/getProduct.php",
        formData
      );
      console.log(response.data);
    } catch (error) {
      console.error("Error during form submission:", error);
    }
  };

  return <div>hi</div>;
}

export default Products;
