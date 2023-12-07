import React, { useState, useEffect } from "react";
import axios from "axios";
import { useParams, useLocation, useNavigate } from "react-router-dom";
function CreateProduct() {
  const { id } = useParams();
  const [formData, setFormData] = useState({
    productname: "",
    description: "",
    price: "",
    id: id,
  });
  const navigate = useNavigate();
  const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;
    setFormData((values) => ({ ...values, [name]: value }));
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await axios.post(
        "http://localhost/e-commerce/backend/api/product.php",
        formData
      );

      console.log(response.data);
    } catch (error) {
      console.error("Error during form submission:", error);
    }
  };
  return (
    <div>
      <h1>Create Product</h1>
      <form onSubmit={handleSubmit}>
        <label htmlFor="productname">Product Name:</label>
        <input
          type="text"
          id="productname"
          name="productname"
          value={formData.productname}
          onChange={handleChange}
          required
        />

        <label htmlFor="description">Description:</label>
        <textarea
          id="description"
          name="description"
          value={formData.description}
          onChange={handleChange}
          required
        ></textarea>

        <label htmlFor="price">Price:</label>
        <input
          type="number"
          id="price"
          name="price"
          step="0.01"
          value={formData.price}
          onChange={handleChange}
          required
        />

        <button type="submit">Create Product</button>
      </form>
    </div>
  );
}

export default CreateProduct;
