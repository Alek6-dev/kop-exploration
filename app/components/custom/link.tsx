import * as React from "react"
import { cva, type VariantProps } from "class-variance-authority"

import { cn } from "@/lib/utils"
import Link from "next/link"

const AVariants = cva(
  "link",
  {
    variants: {
      variant: {
        default: "",
      },
    },
    defaultVariants: {
      variant: "default",
    },
  }
)

export interface AProps
  extends React.LinkHTMLAttributes<HTMLLinkElement>,
    VariantProps<typeof AVariants> {
}

const A = React.forwardRef<HTMLLinkElement, AProps>(
  ({ className, variant, ...props }, ref) => {
    return (
      <Link
        className={cn(AVariants({ variant, className }))}
        // @ts-ignore
        ref={ref}
        {...props}
      />
    )
  }
)
A.displayName = "Link"

export { A, AVariants }
