import React from "react";

const InputField = ({ value, onChange, placeholder, type = "text", name, className = "" }) => {
    return (
        <input
            type={type}
            name={name}
            value={value}
            onChange={onChange}
            placeholder={placeholder}
            className={`input-field ${className}`}
        />
    );
};

export default InputField;
