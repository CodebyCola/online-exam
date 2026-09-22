export default function AuthLayout({ children }: { children: React.ReactNode }) {
    return (
        <div className="page">
            <div className="container" style={{ maxWidth: "640px" }}>
                {children}
            </div>
        </div>
    );
}