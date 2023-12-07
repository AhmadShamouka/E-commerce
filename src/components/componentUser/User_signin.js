// SignIn.jsx

import React, { useState } from "react";

const User_signin = () => {
  const [formData, setFormData] = useState("");

  const handleSignIn = () => {
    console.log("Signing in with:", username, password);
  };

  return (
    <div>
      <h2>Sign In</h2>
      <form>
        <div>
          <label>Username:</label>
          <input
            type="text"
            value={formData}
            onChange={(e) => setFormData(e.target.value)}
            required
          />
        </div>
        <div>
          <label>Password:</label>
          <input
            type="password"
            value={formData}
            onChange={(e) => setFormData(e.target.value)}
            required
          />
        </div>
        <div>
          <button type="button" onClick={handleSignIn}>
            Sign In
          </button>
        </div>
      </form>
    </div>
  );
};

export default User_signin;
