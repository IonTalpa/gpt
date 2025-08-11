import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import { useTranslation } from "react-i18next";

const schema = z.object({
  email: z.string().email(),
  password: z.string().min(1),
});

type FormData = z.infer<typeof schema>;

export default function Login() {
  const { t } = useTranslation();
  const { register, handleSubmit } = useForm<FormData>({ resolver: zodResolver(schema) });
  const onSubmit = (data: FormData) => console.log(data);
  return (
    <form onSubmit={handleSubmit(onSubmit)} className="flex flex-col gap-2">
      <input type="email" placeholder="email" {...register("email")} className="border p-2" />
      <input
        type="password"
        placeholder="password"
        {...register("password")}
        className="border p-2"
      />
      <button type="submit" className="bg-blue-500 text-white p-2">
        {t("login")}
      </button>
    </form>
  );
}
