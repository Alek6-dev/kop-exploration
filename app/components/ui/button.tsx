import * as React from "react"
import { Slot } from "@radix-ui/react-slot"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/lib/utils"

const buttonVariants = cva(
  "rounded uppercase flex-centering w-full outline-none disabled:pointer-events-none disabled:opacity-20 disabled:text-black/70",
  {
    variants: {
      variant: {
        default: "gradient-primary-white text-black font-bold border border-white/25 shadow-[0_0_10px_0_rgba(241,196,69,0.46)] disabled:gradient-primary-white-disabled disabled:border-none disabled:shadow-none",
        secondary:
          "gradient-gray-white text-black font-bold border border-white/25 shadow-[0_0_10px_0_rgba(255,255,255,0.32)]",
        link: "link shadow-none",
        destructive:
          "bg-red text-white hover:bg-red/90 font-bold",
        light:
          "text-white border border-white/10 text-sm font-bold focus:border-white/25 shadow-none",
      },
      size: {
        default: "h-10 px-4",
        sm: "h-8 px-3",
        xs: "h-[32px]"
      },
    },
    defaultVariants: {
      variant: "default",
      size: "default",
    },
  }
)

export interface ButtonProps
  extends React.ButtonHTMLAttributes<HTMLButtonElement>,
    VariantProps<typeof buttonVariants> {
  asChild?: boolean
}

const Button = React.forwardRef<HTMLButtonElement, ButtonProps>(
  ({ className, variant, size, asChild = false, ...props }, ref) => {
    const Comp = asChild ? Slot : "button"
    return (
      <Comp
        className={cn(buttonVariants({ variant, size, className }))}
        ref={ref}
        {...props}
      />
    )
  }
)
Button.displayName = "Button"

export { Button, buttonVariants }
