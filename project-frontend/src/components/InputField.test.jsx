import React from "react";
import { render, screen, fireEvent } from "@testing-library/react";
import InputField from "./InputField";

describe("InputField Component", () => {
    test("renders input with placeholder", () => {
        render(<InputField placeholder="Enter name" />);
        const inputElement = screen.getByPlaceholderText(/enter name/i);
        expect(inputElement).toBeInTheDocument();
    });

    test("renders input with correct type", () => {
        render(<InputField placeholder="Enter password" type="password" />);
        const inputElement = screen.getByPlaceholderText(/enter password/i);
        expect(inputElement).toHaveAttribute("type", "password");
    });

    test("calls onChange handler when value changes", () => {
        const handleChange = jest.fn();
        render(
            <InputField
                placeholder="Enter text"
                value=""
                onChange={handleChange}
            />
        );
        const inputElement = screen.getByPlaceholderText(/enter text/i);
        fireEvent.change(inputElement, { target: { value: "Hello" } });
        expect(handleChange).toHaveBeenCalledTimes(1);
    });

    test("renders input with given value", () => {
        render(<InputField placeholder="Enter value" value="Test" />);
        const inputElement = screen.getByDisplayValue("Test");
        expect(inputElement).toBeInTheDocument();
    });
});
