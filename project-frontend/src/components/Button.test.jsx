import React from "react";
import { render, screen, fireEvent } from "@testing-library/react";
import Button from "./Button";
import '@testing-library/jest-dom';

describe("Button Component", () => {
    test("renders button with text", () => {
        render(<Button>Click Me</Button>);
        const btnElement = screen.getByText(/click me/i);
        expect(btnElement).toBeInTheDocument();
    });

    test("calls onClick handler when clicked", () => {
        const handleClick = jest.fn();
        render(<Button onClick={handleClick}>Click Me</Button>);
        const btnElement = screen.getByText(/click me/i);
        fireEvent.click(btnElement);
        expect(handleClick).toHaveBeenCalledTimes(1);
    });
});
